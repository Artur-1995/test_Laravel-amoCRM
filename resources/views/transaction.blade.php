@extends('layouts.app')

@section('title', Breadcrumbs::render('transactions'))

@section('content')

    <?php
    use Carbon\Carbon;
    ?>
    <template>

        <div class="flex-center position-ref full-height">
            <div class="content">
                <example-component></example-component>
            </div>
        </div>

    </template>
    <p>
        На этой странице располагается таблица с загруженными из amocrm сделками. Колонки:
    <ul>
        <li>ID - id сделки в amocrm;</li>
        <li>Название - название сделки;</li>
        <li>Дата создания;</li>
        <li>Есть контакт - привязаны ли к сделке один или более контактов (да или нет);</li>
        <li>Действия - кнопка “Привязать контакт”.</li>
    </ul>
    Сортировка в таблице - дата создания по убыванию.
    </p>

    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">№</th>
                <th scope="col">ИД</th>
                <th scope="col">Название</th>
                <th scope="col">Дата создания</th>
                <th scope="col">Контакт</th>
                <th scope="col">Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leadsCollection as $key => $lead)
                <tr>
                    <th scope="row">{{ $key + 1 }}</th>
                    <!-- <td class="table-dark" colspan="2">{{ $lead->id }}</td> -->
                    <td>{{ $lead->id }}</td>
                    <td>{{ $lead->name }}</td>
                    <td>{{ Carbon::parse($lead->createdAt)->format('d-m-Y') }}</td>
                    <td>{{ $lead->getContacts() ? 'Да' : 'Нет' }}</td>
                    <td>
                        <a type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#followModal{{ $lead->id }}" href="">Привязать контакт</a>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

    @foreach ($leadsCollection as $key => $lead)
        <!-- Окно привязки контакта -->
        <div class="modal fade" id="followModal{{ $lead->id }}" tabindex="-1" aria-labelledby="followModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="followModalLabel">Контакт</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('add_contact_to_transactions') }}" method="POST">
                            @csrf
                            <div class="col-md-4">
                                <input type="hidden" name="leadId" value="{{ $lead->id }}" />
                                <label for="$key" class="form-label">Контакты</label>
                                <select name="id" id="$key" class="form-select">
                                    @foreach ($contactsCollection as $contact)
                                        <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="mt-3 btn btn-primary">Привязать</button>
                            <a class="mt-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Создать
                                контакт</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Окно создания контакта -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Контакт</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" {{-- method="POST" --}}>
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">ФИО</label>
                            <input type="name" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Раб. тел.</label>
                            <input type="phone" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">Должность</label>
                            <textarea class="form-control" id="position" name="position"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="inputCompany" class="form-label">Компания</label>
                            <select id="inputCompany" class="form-select">
                                <option selected>Choose...</option>
                                <option>...</option>
                            </select>
                        </div>
                        <button type="submit" class="mt-3 btn btn-primary">Создать</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
