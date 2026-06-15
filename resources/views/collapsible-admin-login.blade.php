@props([
    'components' => [],
])
<div
    x-data="{ open: {{ $errors->any() ? 'true' : 'false' }} }"
    style="
        margin-top: 0.5rem;
        border: 1px solid var(--color-base-stroke);
        border-radius: var(--radius-lg, 6px);
        overflow: hidden;
    "
>
    <button
        type="button"
        @click="open = !open"
        style="
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: var(--color-secondary);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
            font-weight: 600;
            color: var(--color-secondary-text);
        "
    >
        <span>Вход для администраторов</span>

        {{-- Стрелка вправо (свёрнут) --}}
        <svg
            x-show="!open"
            style="width: 20px; height: 20px;"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>

        {{-- Стрелка вниз (развёрнут) --}}
        <svg
            x-show="open"
            style="width: 20px; height: 20px;"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        style="padding: 0.75rem 1rem;"
    >
        <x-moonshine::components :components="$components" />
    </div>
</div>
