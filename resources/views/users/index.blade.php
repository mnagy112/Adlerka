<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    @foreach($users as $user)
        <tr>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>
                @switch($user->role)
                    @case(0)
                        User
                        @break
                    @case(1)
                        Admin
                        @break
                @endswitch
                @if($user->role == 0)
                    <form action="{{route('users.changeRole', ['user' => $user->id, 'role' => 1])}}" method="post">
                        @csrf
                        <button type="submit">Set Admin</button>
                    </form>
                @endif
            </td>
            <td>
                @if($user->role == 0)
                    <form action="{{route('users.destroy', ['user' => $user->id])}}" method="post">
                        @csrf
                        <input type="hidden" name="_method" value="delete">
                        <button type="submit">Delete</button>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
</table>
