<script setup>
import HistoryIcon from '@/Components/Icons/HistoryIcon.vue';
import UserIcon from '@/Components/Icons/UserIcon.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

// FIXME: portrait opens page with history open if remembered as such

const calcTextInput = ref(null);
const calcResult = ref(null);

const history = ref([]);

// Whether to show the history panel or not.
// We save this in localStorage so the user's preference is remembered.
const showHistory = ref(localStorage?.getItem('showHistory') === '1');

// Is this a desktop device?
const isDesktop = window.matchMedia('(hover: hover) and (pointer: fine)');

// Run some code at the next animation frame.
function nextTick(f) {
    f(); // We run the code twice to be pedantic.
    requestAnimationFrame(f);
}

// Focus the input if we're on a desktop device.
function focusIfDesktop() {
    if (!isDesktop) return;

    calcTextInput.value.focus();

    nextTick(() => {
        if (!calcTextInput.value) return;
        calcTextInput.value.selectionStart = calcTextInput.value.value.length;
        calcTextInput.value.scrollLeft = calcTextInput.value.scrollWidth;
    });
}

function calcButtonClicked(e) {
    if (!e.target || e.target.id === 'calc-buttons') return; // We only care about clicks on the buttons themselves.

    focusIfDesktop();

    const btn = e.target;
    const symbol = btn.dataset?.value ?? btn.textContent;

    calcTextInput.value.value += symbol;
}

function clearButtonClicked(e) {
    e.stopImmediatePropagation();
    focusIfDesktop();

    calcTextInput.value.value = '';
}

function backspaceButtonClicked(e) {
    e.stopImmediatePropagation();
    focusIfDesktop();

    calcTextInput.value.value = calcTextInput.value.value.slice(0, -1);
}

function equalsButtonClicked(e) {
    e.stopImmediatePropagation();
    focusIfDesktop();

    if (calcTextInput.value.value.trim().length === 0) return;

    const timestamp = new Date();

    axios.get('/api/calc/eval/' + encodeURIComponent(calcTextInput.value.value))
        .then(response => {
            calcResult.value = response.data.toString();

            history.value.push({
                input: calcTextInput.value.value,
                output: response.data.toString(),
                timestamp
            });
        })
        .catch(error => {
            console.error(error);

            calcTextInput.value.parentElement.classList.remove('error');

            requestAnimationFrame(() => {
                if (!calcTextInput.value) return;
                calcTextInput.value.parentElement.classList.add('error');
            });
        });
}

function inputFocusOut(e) {
    nextTick(() => {
        if (!e.target) return;
        e.target.scrollLeft = e.target.scrollWidth;
    });
}

function setShowHistory(show) {
    showHistory.value = show;

    // Save the user's preference for showing the history.
    if (localStorage) {
        localStorage.setItem('showHistory', show ? '1' : '0');
    }
}

function historyButtonClicked(e) {
    setShowHistory(!showHistory.value);
}

function historyItemClicked(entry) {
    calcResult.value = entry.output;
    calcTextInput.value.value = entry.input;
    setShowHistory(false);
}
</script>

<template>

    <Head title="Calculator" />

    <div class="w-screen h-screen bg-zinc-950 text-zinc-50 flex">

        <div class="portrait:fixed z-30 shadow-[0_0_12px_#000] max-w-full h-full bg-[#141519] w-64 overflow-auto flex flex-col transition-transform"
            :class="{ 'landscape:hidden': !showHistory, 'translate-x-[-100%]': !showHistory }">

            <div class="flex-1 flex flex-col">
                <div class="text-center sticky top-0 bg-gradient-to-b from-[#141519] to-transparent p-4">History</div>

                <div class="flex flex-1 flex-col-reverse justify-end break-all">
                    <div v-for="entry in history" @click="historyItemClicked(entry)"
                        class="p-2 pl-4 pr-4 rounded shadow bg-zinc-800 hover:bg-zinc-700 active:bg-zinc-950 transition-colors cursor-pointer m-4 mb-4 first:mb-0 mt-0 font-mono text-sm">
                        <div>{{ entry.output }}</div>
                        <div class="text-xs text-zinc-500">{{ entry.input }}</div>
                    </div>
                </div>

                <div class="sticky bottom-0 bg-[#141519] p-4 text-sm">
                    <div class="rounded shadow bg-zinc-800">
                        <div v-if="!$page.props.auth.user" class="bg-[#bb8729] p-4 rounded text-center">
                            <div class="mb-1">You're not logged in!</div>
                            To save your history, <a :href="route('login')" class="font-bold">log in</a> or <a
                                :href="route('register')" class="font-bold">register</a>.
                        </div>

                        <div v-if="$page.props.auth.user" class="p-4 rounded flex gap-4">
                            <UserIcon class="w-10 h-auto" />

                            <div class="flex-1">
                                <div class="font-bold overflow-hidden whitespace-nowrap overflow-ellipsis">{{
                                    $page.props.auth.user.name }}</div>

                                <div
                                    class="text-xs text-zinc-400 mb-2 overflow-hidden whitespace-nowrap overflow-ellipsis">
                                    {{ $page.props.auth.user.email }}</div>

                                <div class="text-xs">
                                    <Link :href="route('logout')" method="post" as="button">Log out</Link>
                                    &middot;
                                    <Link :href="route('profile.edit')">Settings</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="landscape:hidden fixed z-10 bg-black/50 w-full h-full top-0 left-0 opacity-0 transition-opacity"
            :class="{ 'pointer-events-none': !showHistory, 'opacity-100': showHistory }" @click="setShowHistory(false)">
        </div>

        <div class="flex flex-col overflow-auto flex-1">
            <div id="calc-input" class="bg-[#303032] p-4 m-4 mb-0 rounded">
                <input ref="calcTextInput" type="text" v-bind:autofocus="isDesktop" @blur="inputFocusOut"
                    class="bg-transparent text-right p-0 m-0 w-full h-full font-mono !border-none !shadow-none !outline-none" />
            </div>

            <div id="calc-buttons" class="grid grid-cols-4 flex-1 p-4 gap-4" @click="calcButtonClicked">
                <div class="operation col-span-2" @click="historyButtonClicked">
                    <HistoryIcon class="w-[1.25em] h-auto" />
                </div>
                <div class="operation" @click="clearButtonClicked">CLR</div>
                <div class="operation" @click="backspaceButtonClicked">&#9003;</div>
                <div>(</div>
                <div>)</div>
                <div>%</div>
                <div data-value="^">x<sup>y</sup></div>
                <div>7</div>
                <div>8</div>
                <div>9</div>
                <div class="operator">&div;</div>
                <div>4</div>
                <div>5</div>
                <div>6</div>
                <div class="operator">&Cross;</div>
                <div>1</div>
                <div>2</div>
                <div>3</div>
                <div class="operator" data-value="-">&minus;</div>
                <div>0</div>
                <div data-value=".">&middot;</div>
                <div class="operation" @click="equalsButtonClicked">&equals;</div>
                <div class="operator">&plus;</div>
            </div>
        </div>
    </div>
</template>

<style>
#calc-buttons>div,
#calc-input {
    box-shadow: inset 0 0 2px #000000b3;
}

#calc-input.error {
    animation: calculator-input-error 1s cubic-bezier(0.45, 0, 0.55, 1);
}

#calc-input>input,
#calc-buttons>div {
    font-size: max(min(5vw, 5vh), 1rem);
}

#calc-buttons>div {
    padding: 1rem;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    background-color: #18181b;
    user-select: none;
    transition: transform .1s, background-color .1s;
    border-radius: .25rem;
}

#calc-buttons>div:hover {
    background-color: #303032;
}

#calc-buttons>div:active {
    transform: scale(.95);
    transition: transform .1s;
    background-color: #131316;
}

#calc-buttons>.operator {
    background-color: #bb8729;
}

#calc-buttons>.operator:hover {
    background-color: orange;
}

#calc-buttons>.operator:active {
    background-color: #976121;
}

#calc-buttons>.operation {
    background-color: #00529e;
}

#calc-buttons>.operation:hover {
    background-color: #0079ea;
}

#calc-buttons>.operation:active {
    background-color: #00315e;
}

.\!shadow-none {
    box-shadow: none !important;
}

@keyframes calculator-input-error {
    0% {
        background-color: #303032;
    }

    25% {
        background-color: rgb(255, 0, 0, .33);
    }

    50% {
        background-color: #303032;
    }

    75% {
        background-color: rgb(255, 0, 0, .33);
    }

    100% {
        background-color: #303032;
    }
}
</style>
