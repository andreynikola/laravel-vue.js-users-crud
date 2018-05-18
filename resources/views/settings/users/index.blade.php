@extends('layouts.template')

@section('head')
    @parent
@endsection

@section('header')
    @parent
@endsection

@section('aside')
    @parent
@endsection

@section('container')

<!--Main Content -->
<section id="settings" class="content">
    <!-- Page Content -->
    <div class="wraper container-fluid">
        <div class="page-title">
            <h3 class="title">Настройки предприятия</h3>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default p-0">

                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="{{ route('users.index') }}" aria-expanded="false">
                                <span class="visible-xs"><i class="fa fa-home"></i></span>
                                <span class="hidden-xs">Настройки площадок</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('users.index') }}" aria-expanded="true">
                                <span class="visible-xs"><i class="fa fa-user"></i></span>
                                <span class="hidden-xs">Управление пользователями</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">

                        <div class="tab-pane active" id="setting_tab">
                            <div>
                                <? //include_once $_SERVER['DOCUMENT_ROOT'].'/app/views/settings/ajax-settings.php'; ?>

                                <div class="panel-heading">
                                    <div class="pull-right">

                                        <a href="#" data-target="#edit-user" >
                                            <button class="btn btn-success" v-on:click="add_user()" >
                                                <i class="ion-plus"></i>
                                            </button>
                                        </a>

                                        <a href="#" data-target="#edit-user" >
                                            <button class="btn btn-default" v-on:click="edit_user()" >
                                                <i class="ion-edit"></i>
                                            </button>
                                        </a>

                                        <button class="btn btn-default" v-on:click="remove_user()" >
                                            <i class="ion-trash-a"></i>
                                        </button>

                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="datatable" class="table table-striped table-bordered dataTable">
                                            <thead>
                                            <tr>
                                                <th class="text-center"></th>
                                                <th>Имя</th>
                                                <th>Фамилия</th>
                                                <th>Отчество</th>
                                                <th>Почта</th>
                                                <th>Телефон</th>
                                                <th>Площадка</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($users as $key => $value)
                                                <tr class="text-left" data-id="{{ $value->id }}">
                                                    <td class="text-center"><input type="checkbox" name="idu[]"></td>
                                                    <td>{{ $value->name }}</td>
                                                    <td>{{ $value->surname }}</td>
                                                    <td>{{ $value->father_name }}</td>
                                                    <td>{{ $value->email }}</td>
                                                    <td>{{ $value->phone }}</td>
                                                    <td>{{ $value->platform }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane" id="profile_tab">
                            <? //include $_SERVER['DOCUMENT_ROOT']."/app/views/settings/ajax-user.php"; ?>
                        </div>

                    </div>

                </div>
            </div><!-- .col-md-12 -->
        </div><!-- .row -->
    </div><!-- .wrapper -->

<!-- Модальное окно -->
<div class="modal fade" id="edit-user">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">@{{title}}:</h4>
      </div>  
      <form class="edit-user" method="POST" novalidate action="http://well2/settings/users" accept-charset="UTF-8" v-on:submit.prevent="save_user()">
        <input name="_token" type="hidden" value="h78pfpVYoe0DV9xqd1HZFwakmTJIBMfnZRj9vhfp">
      
        <div class="modal-body">

         <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label>Фамилия:<sup>*</sup></label>
                <input type="text" class="form-control" required name="surname" >
                <div class="help-block"></div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Имя:<sup>*</sup></label>
                <input type="text" class="form-control" required name="name" >
                <div class="help-block"></div>
              </div> 
            </div> 

            <div class="col-md-4">
              <div class="form-group">
                <label>Отчество:</label>
                <input type="text" class="form-control" name="father_name" >
                <div class="help-block"></div>
              </div>  
            </div>

          </div>

         <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label>Email:<sup>*</sup></label>
                <input type="text" class="form-control" required name="email">
                <div class="help-block"></div>
              </div>  
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Телефон:<sup>*</sup></label>
                <input type="text" class="form-control" required name="phone" >
                <div class="help-block"></div>
              </div> 
            </div> 

          </div>

         <div class="row">

            <div class="col-md-8">
                <div class="form-group">
                    <label>Площадки:<sup>*</sup></label>

                    <select name="platform" required multiple class="select2" data-placeholder="Выберите площадки...">
                        @foreach($platforms as $platform)
                            <option value="{{ $platform->id }}">{{ $platform->platform }}</option>
                        @endforeach
                    </select>

                    <div class="help-block"></div>
                </div>
            </div>

         </div>

         <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <label>Группа пользователей:<sup>*</sup></label>
                    <select name="role" required class="select2" data-placeholder="Выберите группу пользователей...">
                    <option value="" disabled=""></option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                    </select>
                    <div class="help-block"></div>
              </div>
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <div class="text-right">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Отменить" />
            <input type="submit" class="btn btn-primary" value="Сохранить" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

</section>

<script type="text/javascript" src="{{ asset('js/settings/users.js') }}"></script>
@endsection