<div>
    <center>
        <h1>
            {{ Auth::user() }}
        </h1>
        <form action="{{ route('login.post') }}" method="POST">
            {{ csrf_field() }}
            <div>
                <label for="email">
                    Email
                </label>
                <br><br>
                <input type="email" name="email" id="email" placeholder="Masukan Email...">
            </div>
            <br>
            <div>
                <label for="password">
                    Password
                </label>
                <br><br>
                <input type="password" name="password" id="password" placeholder="Masukan password...">
            </div>
            <br>
            <div>
                <input type="submit" value="Login">
            </div>
        </form>
    </center>
</div>
