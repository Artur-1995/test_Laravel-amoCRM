@extends('layouts.app')

@section('title', Breadcrumbs::render('history'))

@section('content')
    <?php
    use Carbon\Carbon;
    ?>

    <p>
        На этой странице должна располагаться таблица с историей действий в приложении. Колонки:
        Дата и время;
        Действие;
        Результат (успешность операции или ошибка).
    </p>

    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">№</th>
                <th scope="col">Дата и время</th>
                <th scope="col">Действие</th>
                <th scope="col">Результат</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $key => $log)
                <tr>
                    <th scope="row">{{ $log->id }}</th>
                    <!-- <td class="table-dark" colspan="2">{{ $log->id }}</td> -->
                    <td>{{ Carbon::parse($log->created_at)->format('d-m-Y') }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->result }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="container">
        @foreach ($logs as $log)
            {{ $log->name }} 
        @endforeach
    </div>
    
    {{ $logs->links() }}
@endsection
