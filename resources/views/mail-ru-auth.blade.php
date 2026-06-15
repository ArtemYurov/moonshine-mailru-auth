@props([
    'components' => [],
])
<style>
    #mailru-auth {
        height: 50px;
        min-height: 40px;
        margin: 0;
        padding: 0;
        display: inline-block;
        white-space: nowrap;
        width: 100%;
    }

    #mailru-auth a, button {
        display: inline;
    }

    #mailru-auth .mailru-btn {
        height: 100%;
        width: 100%;
        cursor: pointer;
        color: #fff;
        background-color: #0077FF;
        border-radius: 6px;
        font-size: 15px;
        font-family: Arial, Helvetica, sans-serif;
        position: relative;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        border: none;
    }
    #mailru-auth .with_pic {
        padding: 0 60px 0 0;
    }

    #mailru-auth span {
        overflow: hidden;
        text-overflow: ellipsis;
        position: absolute;
        left: 45px;
        top: 50%;
        margin-top: -11px;
    }

    #mailru-auth .with_pic span {
        right: 56px;
    }

    #mailru-auth .mailru-btn::after {
        content: '';
        display: block;
        border-radius: 6px;
        border: 1px solid rgba(0, 0, 0, .12);
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 10;
    }

    #mailru-auth .mailru-btn::before {
        content: '';
        display: block;
        width: 20px;
        height: 20px;
        position: absolute;
        top: 50%;
        left: 20px;
        margin-top: -10px;
        background-image: url(data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgMTUgMTUiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik03LjIzOTY3IDAuMzM3OTgyQzExLjE4NjQgMC4zMzc5ODIgMTQuNDAxNyAzLjU1MzMzIDE0LjQwMTcgNy41MDAwMkMxNC40MDE3IDkuOTU4ODIgMTMuMjU0MyAxMS4xMTg5IDExLjgwNDIgMTEuMTE4OUMxMC45NTk0IDExLjExODkgMTAuMjI4MSAxMC42Nzc1IDkuODExOTYgMTAuMDIxOUM5LjE1NjI4IDEwLjY5MDIgOC4yNDg0MSAxMS4xMDYzIDcuMjM5NjcgMTEuMTA2M0M1LjI0NzQyIDExLjEwNjMgMy42MzM0NCA5LjQ5MjI4IDMuNjMzNDQgNy41MDAwMkMzLjYzMzQ0IDUuNTA3NzYgNS4yNDc0MiAzLjg5Mzc4IDcuMjM5NjcgMy44OTM3OEM5LjIzMTkzIDMuODkzNzggMTAuODQ1OSA1LjUwNzc2IDEwLjg0NTkgNy41MDAwMlY4LjY3MjY4QzEwLjg0NTkgOS4yNjUzMSAxMS4yNjIgOS42ODE0MiAxMS44MDQyIDkuNjgxNDJDMTIuNDQ3MyA5LjY4MTQyIDEyLjk3NjkgOS4xMzkyMiAxMi45NzY5IDcuNTAwMDJDMTIuOTc2OSA0LjMzNTEgMTAuNDA0NiAxLjc2MjgyIDcuMjM5NjcgMS43NjI4MkM0LjA3NDc2IDEuNzYyODIgMS41MDI0OCA0LjMzNTEgMS41MDI0OCA3LjUwMDAyQzEuNTAyNDggMTAuNjY0OSA0LjA3NDc2IDEzLjIzNzIgNy4yMzk2NyAxMy4yMzcyQzkuMDY4MDEgMTMuMjM3MiAxMC4zMjg5IDEyLjMyOTQgMTAuMzI4OSAxMi4zMjk0TDExLjI3NDYgMTMuNDEzN0MxMS4yNzQ2IDEzLjQxMzcgOS42NjA2NSAxNC42NjIxIDcuMjM5NjcgMTQuNjYyMUMzLjI5Mjk5IDE0LjY2MjEgMC4wNzc2MzY3IDExLjQ0NjcgMC4wNzc2MzY3IDcuNTAwMDJDMC4wNzc2MzY3IDMuNTUzMzMgMy4yOTI5OSAwLjMzNzk4MiA3LjIzOTY3IDAuMzM3OTgyWk01LjA1ODI4IDcuNTAwMDJDNS4wNTgyOCA4LjY5Nzg5IDYuMDI5MTkgOS42ODE0MiA3LjIzOTY3IDkuNjgxNDJDOC40Mzc1NSA5LjY4MTQyIDkuNDIxMDcgOC42OTc4OSA5LjQyMTA3IDcuNTAwMDJDOS40MjEwNyA2LjI4OTUzIDguNDM3NTUgNS4zMTg2MiA3LjIzOTY3IDUuMzE4NjJDNi4wMjkxOSA1LjMxODYyIDUuMDU4MjggNi4yODk1MyA1LjA1ODI4IDcuNTAwMDJaIiBmaWxsPSJ3aGl0ZSIvPgo8L3N2Zz4=);
    }

    #mailru-auth b {
        position: relative;
        z-index: 20;
    }

    #mailru-auth img {
        top: 0;
        right: 0;
        height: 100%;
        border-radius: 0 6px 6px 0;
        border: none;
        position: absolute;
        z-index: 5;
    }
</style>

<form
    {{ $attributes->merge(['class' => 'form space-elements', 'method' => 'POST']) }}
    @if(empty($attributes->get('id')))
        x-id="['form']" :id="$id('form')"
    @endif
>
    @if(strtolower($attributes->get('method', '')) !== 'get')
        @csrf
    @endif

    <div id="mailru-auth">
        <button type="submit" class="mailru-btn">
            <span>Почта Mail.ru</span>
        </button>
    </div>

    <x-moonshine::components :components="$components" />

    @if(session()->has('alert'))
        <x-moonshine::alert type="error" :removable="false" style="margin-top: 0.5rem;">
            {{ session('alert') }}
        </x-moonshine::alert>
    @endif

</form>
