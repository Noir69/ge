<div>
    <!-- Simplicity is the essence of happiness. - Cedric Bledsoe -->
    <table>
            <thead>
                <tr>
                    <th>reserv</th>
                    <th>paid</th>
                    <th>stat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pa as $teacher)
                    <tr>
                        <td>{{ $teacher->reservations_id }}</td>
                        <td>{{ $teacher->paid }}</td>
                        <td>{{ $teacher->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</div>
