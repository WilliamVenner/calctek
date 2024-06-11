<script setup>
import HistorySidebar from '@/Components/Calculator/HistorySidebar.vue';
import HistoryIcon from '@/Components/Icons/HistoryIcon.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

// Stack of historic calculations.
const historyStack = ref(page.props.calc_history.map(entry => ({
    timestamp: new Date(entry.created_at),
    input: entry.input_expression,
    output: entry.output
})));

// #calc-input>input
const calcTextHTMLInput = ref(null);

// Whether to show the history panel or not.
// * We save this in localStorage so the user's preference is remembered.
// * We use separate states for portrait and landscape orientations for UX reasons.
const showHistoryLandscape = ref(localStorage?.getItem('showHistoryLandscape') === '1');
const showHistoryPortrait = ref(false);

// Is this a desktop device?
const isDesktop = window.matchMedia('(hover: hover) and (pointer: fine)');

// Is the current device orientation landscape?
function isLandscape() {
    return window.matchMedia('(orientation: landscape)').matches;
}

// If the history panel is visible and the device goes from portrait->landscape->portrait, hide it.
{
    let curOrientation = isLandscape();

    window.matchMedia('(orientation: landscape)').addEventListener('change', m => {
        if (m.matches !== curOrientation) {
            curOrientation = m.matches;
            showHistoryPortrait.value = false;
        }
    });
}

// Run some code at the next animation frame.
function nextTick(f) {
    f(); // We run the code twice to be pedantic.
    requestAnimationFrame(f);
}

// Focus the input if we're on a desktop device.
// From a UX perspective, automatically focusing the input field is only appropriate on desktop devices as they do not have a virtual keyboard that pops up and covers the screen when an input field is focused.
function focusIfDesktop() {
    if (!isDesktop) return;

    calcTextHTMLInput.value.focus();

    nextTick(() => {
        if (!calcTextHTMLInput.value) return;
        calcTextHTMLInput.value.selectionStart = calcTextHTMLInput.value.value.length;
        calcTextHTMLInput.value.scrollLeft = calcTextHTMLInput.value.scrollWidth;
    });
}

// When one of the calculator buttons is clicked...
function calcButtonClicked(e) {
    if (!e.target || e.target.id === 'calc-buttons') return; // We only care about clicks on the buttons themselves.

    focusIfDesktop();

    const btn = e.target;
    const symbol = btn.dataset?.value ?? btn.textContent;

    // Append the respective symbol/operator to the input field.
    calcTextHTMLInput.value.value += symbol;
}

// When the CLR button is clicked...
function clearButtonClicked(e) {
    e.stopImmediatePropagation();
    focusIfDesktop();

    calcTextHTMLInput.value.value = '';
}

// When the backspace button is clicked...
function backspaceButtonClicked(e) {
    e.stopImmediatePropagation();
    focusIfDesktop();

    calcTextHTMLInput.value.value = calcTextHTMLInput.value.value.slice(0, -1);
}

// When the equals button is clicked...
function equalsButtonClicked(e) {
    e.stopImmediatePropagation();
    focusIfDesktop();

    // Don't do anything if the user's input is blank.
    if (calcTextHTMLInput.value.value.trim().length === 0) return;

    const timestamp = new Date();

    axios.get('/calc/eval/' + encodeURIComponent(calcTextHTMLInput.value.value))
        .then(response => {
            calcTextHTMLInput.value.parentElement.classList.remove('error');

            const entry = {
                input: calcTextHTMLInput.value.value,
                output: response.data.toString(),
                timestamp
            };

            calcTextHTMLInput.value.value = entry.output;
            historyStack.value.push(entry);
        })
        .catch(error => {
            console.error(error);

            if (!error.response || error.response.status === 503) {
                alert('The calculator service is currently unavailable or you are offline. Please try again later.');
            } else if (error.response.status === 500) {
                alert('An internal server error occurred and has been logged. Please try again later.');
            } else {
                calcTextHTMLInput.value.parentElement.classList.remove('error');

                requestAnimationFrame(() => {
                    if (!calcTextHTMLInput.value) return;
                    calcTextHTMLInput.value.parentElement.classList.add('error');
                });
            }
        });
}

// When the text input loses focuses, horizontally scroll it to the end.
// This keeps the last character(s) of the user's input visible instead of scrolling back to the start (default browser behaviour.)
function inputFocusOut(e) {
    nextTick(() => {
        if (!e.target) return;
        e.target.scrollLeft = e.target.scrollWidth;
    });
}

// When the user presses a key in the input field...
function inputKeyUp(e) {
    // If the ENTER key is pressed...
    if (e.keyCode === 13) {
        // "Click" the equals button
        equalsButtonClicked(e);
    }
}

// When the history button is clicked...
function historyButtonClicked() {
    // Toggle the respective showHistory[...] state...
    if (!isLandscape()) {
        showHistoryPortrait.value = !showHistoryPortrait.value;
    } else {
        showHistoryLandscape.value = !showHistoryLandscape.value;

        // Save the user's preference for showing the history in landscape mode.
        if (localStorage) {
            localStorage.setItem('showHistoryLandscape', showHistoryLandscape.value ? '1' : '0');
        }
    }
}

// When an item in the history panel is clicked...
function historyItemClicked(entry) {
    // Set the input field value to the history item's output.
    calcTextHTMLInput.value.value = entry.output;

    if (!isLandscape()) {
        // Close the history sidebar if we're in portrait mode.
        showHistoryPortrait.value = false;
    }
}

// This computed value stores the last value at the top of the history stack.
// It's used to animate the ticker tape effect.
const calcTickerAnimationValue = computed(() => historyStack.value.slice(-1)[0]?.input ?? '');

</script>

<template>

    <Head title="Calculator" />

    <div class="w-screen h-screen bg-zinc-950 text-zinc-50 flex">
        <HistorySidebar
            :historyItemClick="historyItemClicked"
            :showHistoryLandscape="showHistoryLandscape"
            :historyStack="historyStack"
            v-model:showHistoryPortrait="showHistoryPortrait"
        />

        <div class="flex flex-col overflow-auto flex-1">
            <!-- Input Text Field -->
            <div id="calc-input"
                class="bg-[#303032] m-4 mb-0 rounded relative overflow-hidden shrink-0 shadow-[inset_0_0_2px_#000000b3]">
                <Transition name="calc-input">
                    <div class="calc-input-ticker w-full h-full flex-1 bg-transparent p-4 m-0 h-full font-mono absolute top-0 left-0 pointer-events-none whitespace-nowrap"
                        :key="historyStack.length">{{ calcTickerAnimationValue }}</div>
                </Transition>

                <input ref="calcTextHTMLInput" type="text" v-bind:autofocus="isDesktop" @blur="inputFocusOut" @keyup="inputKeyUp"
                    class="flex-1 w-full h-full bg-transparent p-4 m-0 font-mono whitespace-nowrap" />
            </div>

            <!-- Calculator Buttons Grid -->
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
/**** TEXT SIZE SCALING ****/

#calc-input>*,
#calc-buttons>div {
    font-size: max(min(5vw, 5vh), 1rem);
}

/**** CALCULATOR INPUT FIELD ****/

#calc-input.error {
    animation: calc-input-error 1s cubic-bezier(0.45, 0, 0.55, 1);
}

#calc-input>* {
    outline: none !important;
    border: none !important;
    box-shadow: none !important;
    line-height: initial !important;
}

/**** CALCULATOR BUTTONS ****/

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
    box-shadow: inset 0 0 2px #000000b3;
}

#calc-buttons>div:hover {
    background-color: #303032;
}

#calc-buttons>div:active {
    transform: scale(.95);
    transition: transform .1s;
    background-color: #131316;
}

/**** OPERATORS ****/
/* Divide, multiply, subtract, add */

#calc-buttons>.operator {
    background-color: #bb8729;
}

#calc-buttons>.operator:hover {
    background-color: orange;
}

#calc-buttons>.operator:active {
    background-color: #976121;
}

/**** OPERATIONS ****/
/* History, CLR, backspace, equals, etc. */

#calc-buttons>.operation {
    background-color: #00529e;
}

#calc-buttons>.operation:hover {
    background-color: #0079ea;
}

#calc-buttons>.operation:active {
    background-color: #00315e;
}

/**** CALCULATOR INPUT ANIMATIONS ****/

@keyframes calc-input-error {
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

.calc-input-ticker {
    transform: translateY(-100%);
}

.calc-input-enter-active {
    animation: calc-input-ticker-up-out 0.33s ease-in-out;
}

.calc-input-enter-active+input {
    animation: calc-input-ticker-up-in 0.33s ease-in-out;
}

@keyframes calc-input-ticker-up-out {
    0% {
        transform: translateY(0%);
    }

    100% {
        transform: translateY(-100%);
    }
}

@keyframes calc-input-ticker-up-in {
    0% {
        transform: translateY(100%);
    }

    100% {
        transform: translateY(0%);
    }
}
</style>
