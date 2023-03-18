<div>
    <h1>Bingo Game</h1>
    <table>
    @foreach ($numbers as $row)
    <tr>

        @foreach ($row as $number)
            <td>
                @if ($number === 0)
                    <div class="blank"></div>
                @else
                    <div class="number">{{ $number }}</div>
                @endif
            </td>
        @endforeach

    </tr>
    @endforeach
</table>




    
</div>