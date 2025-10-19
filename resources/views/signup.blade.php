<div>
    <form action="/signup" method="post">
        @csrf
        <div>
            <label>Name</label>
            <input type="text" name="name">
            <span>@error('name'){{ $message }}@enderror</span>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email">
            <span>@error('email'){{ $message }}@enderror</span>
        </div>
        <div>
            <label>Password</label>
            <input type="text" name="password">
            <span>@error('password'){{ $message }}@enderror</span>
        </div>
        <input type="submit" value="Sign Up">
    </form>
</div>
