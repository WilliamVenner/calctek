<script setup>
import UserIcon from '@/Components/Icons/UserIcon.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps([
    'showHistoryLandscape',
    'showHistoryPortrait',
    'historyStack',
    'historyItemClick',
]);
</script>

<template>
    <!-- History Sidebar -->
    <div class="portrait:fixed z-30 shadow-[0_0_12px_#000] max-w-full h-full bg-[#141519] w-64 overflow-auto flex flex-col portrait:transition-transform"
        :class="{ 'landscape:hidden': !props.showHistoryLandscape, 'portrait:translate-x-[-100%]': !props.showHistoryPortrait }"
        id="calc-history-sidebar">

        <div class="flex-1 flex flex-col">
            <div class="text-center sticky top-0 bg-gradient-to-b from-[#141519] to-transparent p-4">History</div>

            <!-- History entries -->
            <div class="flex flex-1 flex-col-reverse justify-end break-all" id="calc-history-entries">
                <div v-for="entry in props.historyStack" @click="props.historyItemClick(entry)"
                    class="p-2 pl-4 pr-4 rounded shadow bg-zinc-800 hover:bg-zinc-700 active:bg-zinc-950 transition-colors cursor-pointer m-4 mb-4 first:mb-0 mt-0 font-mono text-sm">
                    <div class="calc-history-entry-output">{{ entry.output }}</div>
                    <div class="calc-history-entry-input text-xs text-zinc-500">{{ entry.input }}</div>
                </div>
            </div>

            <div class="sticky bottom-0 bg-[#141519] p-4 text-sm">
                <div class="rounded shadow bg-zinc-800">
                    <!-- "Log in to save history" warning -->
                    <div v-if="!$page.props.auth.user" class="bg-[#bb8729] p-4 rounded text-center">
                        <div class="mb-1">You're not logged in!</div>
                        To save your history, <a :href="route('login')" class="font-bold">log in</a> or <a
                            :href="route('register')" class="font-bold">register</a>.
                    </div>

                    <!-- Logged in user -->
                    <div v-if="$page.props.auth.user" class="p-4 rounded flex gap-4">
                        <UserIcon class="w-10 h-auto" />

                        <div class="flex-1">
                            <div class="font-bold overflow-hidden whitespace-nowrap overflow-ellipsis">{{
                                $page.props.auth.user.name }}</div>

                            <div class="text-xs text-zinc-400 mb-2 overflow-hidden whitespace-nowrap overflow-ellipsis">
                                {{ $page.props.auth.user.email }}</div>

                            <div class="text-xs">
                                <Link :href="route('profile.edit')">My Account</Link>
                                &middot;
                                <Link :href="route('logout')" method="post" as="button">Log out</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- History Sidebar Background -->
    <div class="landscape:hidden fixed z-10 bg-black/50 w-full h-full top-0 left-0 opacity-0 transition-opacity"
        :class="{ 'pointer-events-none': !props.showHistoryPortrait, 'opacity-100': props.showHistoryPortrait }"
        @click="$emit('update:showHistoryPortrait', false)">
    </div>
</template>
