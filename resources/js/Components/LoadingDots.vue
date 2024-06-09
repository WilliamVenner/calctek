<template>
    <div class="loading-dots">
        <div class="ld-1"></div>
        <div class="ld-2"></div>
        <div class="ld-3"></div>
        <div class="ld-4"></div>
    </div>
</template>

<style>
    .loading-dots {
        display: inline-grid;
        grid-template-columns: auto auto auto;
        gap: 0.5em;

        align-self: center;
    }
    .loading-dots > div {
        width: 1em;
        height: 1em;
        background-color: currentColor;
        border-radius: 50%;
        transform-origin: center center;
        animation-timing-function: cubic-bezier(0.65, 0, 0.35, 1);
        animation-duration: .75s;
        animation-iteration-count: infinite;
    }

    .loading-dots > .ld-3, .loading-dots > .ld-4 {
        grid-column: 3;
        grid-row: 1;
    }

    .loading-dots > .ld-2, .loading-dots > .ld-3 {
        animation-name: ld-inner;
    }

    .loading-dots > .ld-1, .loading-dots > .ld-4 {
        animation-name: ld-terminals;
    }

    .loading-dots > .ld-4 {
        animation-direction: reverse;
    }

    @keyframes ld-terminals {
        0% {
            transform: scale(0);
        }
        100% {
            transform: scale(1);
        }
    }

    @keyframes ld-inner {
        0% {
            transform: translateX(calc(-100% - 0.5em));
        }
        100% {
            transform: translateX(0);
        }
    }
</style>
