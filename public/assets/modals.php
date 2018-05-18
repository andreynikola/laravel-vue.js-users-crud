<!--Добавить пользователей-->
<? /* if ( strpos($_SERVER['REQUEST_URI'], 'settings/users') !== false ) { ?>

<div class="modal fade" id="edit-user">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Информация о пользователе:</h4>
      </div>  
      <form class="edit-user" novalidate method="POST">
        <div class="modal-body">

          <input type="hidden" name="operation" value="add">

          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label>Логин:<sup>*</sup></label>
                <input type="text" required class="form-control" disabled name="login" >
                <div class="help-block"></div>
              </div> 
            </div>

          </div>

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
                    <? 
                    $platforms = getPlatforms($link);
                    foreach ($platforms as $key => $value) { ?>
                      <option value="<?=$key?>"><?=$platforms[$key]['name']?></option>
                    <? } ?>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>

          </div>

         <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                  <label>Группа пользователей:<sup>*</sup></label>
                  <select name="level" required class="select2" data-placeholder="Выберите группу пользователей...">
                  <option value="" disabled=""></option>
                  <? 
                  $level = getUsersGroup($link);
                  foreach ($level as $key => $value) { ?>
                    <option value="<?=$key;?>"><?=$value;?></option>
                  <? } ?>
                  </select>
                  <div class="help-block"></div>
              </div>
            </div>

          </div>

         <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <a href="#" data-action="reset-password" >Сбросить пароль</a>
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

<? } ?>




<? if ( strpos($_SERVER['REQUEST_URI'], 'matrix') !== false ) { ?>
<!-- ВСПЛЫВАЮЩЕЕ ОКНО(ОТЧЕТ ПО САМООЦЕНКЕ) -->
<div class="modal fade" id="selfModal" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="row">
          <!-- <div class="col-md-offset-3 col-md-3">
            <p><b>Аудит № </b><span id="audit"><?=$_GET['id'];?></span><br></p>
          </div> -->
          <div class="col-md-6">
            <p><b>Требование № </b><span id="row"></span><br></p>
          </div>
          <div class="col-md-offset-3 col-md-3">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
        </div>
        
      </div>
      <div class="modal-body">

          <div class="row">
            <? if ($_GET['type'] == 'calendar' || $_GET['type'] == 'self-evaluation') { ?>
            <div class="col-md-6">
              <div class="form-group">
                <label>Плановый срок<sup>*</sup></label>
                <input type="text" class="form-control datepicker" name="date-plan" data-date-format="dd.mm.yyyy">
                <div class="help-block"></div>
              </div>
            </div>
            <? } ?>
            
            <? if ($_GET['type'] == 'calendar') { ?>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Фактический срок<sup>*</sup></label>
                  <input type="text" class="form-control datepicker" name="fact-date" data-date-format="dd.mm.yyyy">
                  <div class="help-block"></div>
                </div>
              </div>
            <? } ?>

          </div>
    
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label >Статус<sup>*</sup></label>
                <select class="form-control" name='status'>
                  <option value="0" class=fivet></option>
                  <option value="5" class=fivet>N/A</option>
                  <option value="1" class=onet>более 90% </option>
                  <option value="2" class=twot>от 71 до 90%</option>
                  <option value="3" class=threet>от 41 до 70%</option>
                  <option value="4" class=fourt>менее 40%</option>
                </select>
                <div class="help-block"></div>
              </div>
            </div>
            
            <? if ($_GET['type'] == 'calendar' || $_GET['type'] == 'self-evaluation') { ?>
            <div class="col-md-6">
              <div class="form-group">
                <? $users = getUsers($link); ?>

                <label for="fio">ФИО ответственного<sup>*</sup></label>
                <select class="form-control" name='fio'>
                  <option value=""></option>
                  <? foreach ($users as $key => $value) { ?>
                    <option value="<?=$users[$key]['login'];?>"><?=$users[$key]['name'];?></option>
                  <? } ?>
                </select>

                <!-- <input type="text" class="form-control" id="fio" name="fio"> -->
                <div class="help-block"></div>
              </div>
            </div>
            <? } ?>
          </div>

          <? if ( $_GET['type'] == 'calendar' ) { ?>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label >Согласованность</label>
                <select class="form-control" name='agreed'>
                  <option value="0">Не согласовано</option>
                  <option value="1">Согласовано</option>
                </select>
                <div class="help-block"></div>
              </div>
            </div>  
          </div>
          <? } ?>

          <p><b>Необходимые мероприятия</b><br><span id="demand"></span></p>
          
          <? if ( $_GET['type'] != 'calendar' ) { ?>
            <div class="form-group">
              <label>
                Выявленные несоответствия<sup>*</sup>
              </label>
              <textarea class="form-control" rows="5" name="actions"></textarea>
              <div class="help-block"></div>
            </div>
          <? } ?>

          <? if ( $_GET['type'] == 'calendar' ) { ?>
            <div class="form-group">
              <label>
                Причина невыполнения<sup>*</sup>
              </label>
              <textarea class="form-control" rows="5" name="reason-failure"></textarea>
              <div class="help-block"></div>
            </div>
          <? } ?>

          <? if ( $_GET['type'] == 'calendar' ) { ?>
            <div class="form-group">
              <label>
                Мероприятия по устранению<sup>*</sup>
              </label>
              <textarea class="form-control" rows="5" name="recommendations"></textarea>
              <div class="help-block"></div>
            </div>
          <? } ?>
          

      </div>
      <div class="modal-footer">
        <div class="text-right">
          <input type="button" class="btn btn-default" id="cancel" value="Отменить" />
          <input type="button" class="btn btn-primary" id="save" value="Сохранить" />
        </div>
      </div>
    </div>
  </div>
</div>
<!-- ОБНОВЛЕНИЕ ТРЕБОВАНИЙ(ОТЧЕТ ПО САМООЦЕНКЕ) -->
<div class="modal fade" id="updateMatrix" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Доступны обновления</h4>
      </div>
      <div class="modal-body">
        <p>Часть требований, используемых в данной самооценке, устарели и информация по ним стала неактуальной.
          Пожалуйста обновите требования!</p>
      </div>
      <div class="modal-footer">
        <div class="text-right">
          <input type="button" class="btn btn-default" data-dismiss="modal" value="Продолжить" />
          <input type="button" class="btn btn-primary" onclick="updateMatrix(<?=$_GET['id'];?>);" value="Обновить требования" />
        </div>
      </div>
    </div>
  </div>
</div>
<? } ?>

<? if ( strpos($_SERVER['REQUEST_URI'], 'analytics') !== false ) { ?>
<!-- ВСПЛЫВАЮЩЕЕ ОКНО(ГРАФИК:ПЕРЕЧИСЛЕНИЕ ВИДОВ РАБОТ) -->
<div class="modal fade" id="worksModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Укажите виды работ</h4>
      </div>  
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <ul id="show_hide" class="header-filter">
              <li>
                <a href="#" onclick="showAll(this);">Выбрать все</a>
              </li>
              <li>
                <a href="#" onclick="hideAll(this);">Снять все</a>
              </li>
            </ul>       
          </div>
          <div id="chart-labels">

          </div>
        </div>

      </div>
      <div class="modal-footer">
        <div class="text-right">
          <!-- <input type="button" class="btn btn-default" data-dismiss="modal" value="Отменить" /> -->
          <input type="button" class="btn btn-primary" data-dismiss="modal" id="save" value="Подтвердить" />
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ВСПЛЫВАЮЩЕЕ ОКНО(ГРАФИК:ПЕРЕЧИСЛЕНИЕ ПРЕДПРИЯТИЙ) -->
<div class="modal fade" id="factoryModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Укажите предприятия</h4>
      </div>  
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <ul id="show_hide" class="header-filter">
              <li>
                <a href="#" onclick="showAll(this);">Выбрать все</a>
              </li>
              <li>
                <a href="#" onclick="hideAll(this);">Снять все</a>
              </li>
            </ul>       
          </div>
          <div id="chart-factory">

          </div>
        </div>

      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-md-6">
            <p class="text-danger text-left m-t-5 m-b-0 hide"><i class="ion-information-circled"></i> Укажите не менее 3 предприятий</p>
          </div> 
          <div class="col-md-6 text-right">
            <input type="button" class="btn btn-primary" data-dismiss="modal" value="Подтвердить" />
          </div> 
        </div>
      </div>
    </div>
  </div>
</div>


<!-- ВСПЛЫВАЮЩЕЕ ОКНО(ГРАФИК:СТОЛБЧАТАЯ ДИАГРАММА) -->
<div class="modal fade" id="chartWorksModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="detail_title">Укажите виды работ</h4>
      </div>  
      <div class="modal-body">
  <div id="detail_company">
      <div id="years"></div><div id="pdf"></div>
      <table id="detail_table"><tbody></tbody></table>
    <div id="detail_delimiter"></div>
  </div>

      </div>
      <!-- <div class="modal-footer"></div> -->
    </div>
  </div>
</div>

<!-- ВСПЛЫВАЮЩЕЕ ОКНО(ГРАФИК:РАДАР) -->
<div class="modal fade" id="chartRadarModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>  
      <div class="modal-body">

      <div id="detail_screen">
         <p class="years"></p>
            <div id="table">
               <table>
                 <tbody></tbody>
               </table>
               <div id="delimiter"></div>
            </div>
      </div>

      </div>
      <!-- <div class="modal-footer"></div> -->
    </div>
  </div>
</div>
<? } ?>

<? if ( strpos($_SERVER['REQUEST_URI'], 'tasks') !== false ) { ?>
<!-- ВСПЛЫВАЮЩЕЕ ОКНО(РЕДАКТИРОВАТЬ ЗАДАЧУ) -->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-left">Новая задача</h4>
                </div>
                <form id="update-task" novalidate method="POST">
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-7">

                                <input type="hidden" value="" name="task-id"/>

                                <div class="form-group">
                                    <label>Наименование задачи:</label>
                                    <input type="text" required class="form-control" name="task-name" placeholder="">
                                    <div class="help-block"></div>
                                </div>

                                <div class="form-group">
                                    <label>Описание задачи:</label>
                                    <textarea class="form-control" required name="task-description" rows="5"></textarea>
                                    <div class="help-block"></div>
                                </div>

                                <div class="form-group">
                                    <label>Надзорный орган:</label>
                                    <select name="task-authority" required class="select2" data-placeholder="Выберите надзорный орган...">
                                    <option value="">Выберите надзорный орган</option>
                                    <? 
                                    foreach ($authority as $key => $value) { ?>
                                      <option value="<?=$value?>"><?=$value?></option>
                                    <? } ?>
                                    </select>
                                    <div class="help-block"></div>
                                </div>

                                <!-- <div class="form-group">
                                    <label>Надзорный орган:</label>
                                    <input type="text" class="form-control" name="task-authority" placeholder="">
                                    <div class="help-block"></div>
                                </div> -->
                                
                                <label>Чек лист:</label>

                                <div id="subtask" class="row">
                                  
                                  <ul class="list-unstyled sortable"></ul>

                                  <div class="col-md-10">
                                    <div class="form-group">
                                      <input class="form-control" type="text" name="subtask" placeholder="Что нужно сделать">
                                      <div class="help-block"></div>
                                    </div>
                                  </div>  
                                  <div class="col-md-2 form-inline nowrap">
                                    <div class="form-group m-5">
                                      <a href="javascript:void(0);" onclick="addSubtask(event);">
                                        <i class="fa ion-checkmark text-success"></i>
                                      </a>
                                    </div>
                                    <div class="form-group m-5">
                                      <a href="javascript:void(0);" onclick="clearSubtask(event);">
                                        <i class="fa ion-close text-danger"></i>
                                      </a>
                                    </div>
                                  </div>

                                </div>                           

                            </div>

                            <div class="col-md-5">

                                <div class="form-group">
                                    <label>Крайний срок:</label>
                                    <input type="text" class="form-control datepicker" required name="task-deadline">
                                    <div class="help-block"></div>
                                </div>

                                <div class="form-group">
                                    <label>Напоминать начиная с:</label>
                                    <input type="text" class="form-control datepicker" required name="task-start-noty" id="task-start-noty">
                                    <!-- <div class="help-block"></div> -->

<div>
  <a class="m-r-10" href="javascript:void(0);" onclick="setFromDate('7 days')"><small>за неделю</small></a>
  <a class="m-r-10" href="javascript:void(0);" onclick="setFromDate('14 days')"><small>за 2 недели</small></a>
  <a class="m-r-10" href="javascript:void(0);" onclick="setFromDate('1 month')"><small>за месяц</small></a>
  <a class="" href="javascript:void(0);" onclick="setFromDate('2 month')"><small>за 2 месяца</small></a>
</div>  

                                </div>


                                <!-- <div class="form-inline">
                                  <div class="form-group">
                                      <label>Периодичность уведомлений:</label>
                                      <input type="number" value="1" class="form-control" required name="task-frequency" id="task-frequency">
                                      <select name="task-dimension" class="form-control">
                                          <option value="0">Часы</option>
                                          <option value="1" selected>Дни</option>
                                      </select>
                                      <div class="help-block"></div>
                                  </div>
                                </div> -->



                                <div class="form-group">
                                    <label>Приоритет:</label>
                                    <select name="task-priority" class="form-control">
                                        <option value="0">Не срочно</option>
                                        <option value="1">Срочно</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <label>Постановщик:</label>
                                    <input type="hidden" name="task-director" value="<?=$_COOKIE['login'];?>" />
                                    <p id="director"><?=getUserName($link,$_COOKIE['login']);?></p>
                                </div>

                                <div class="form-group">
                                    <label>Ответственный:</label>
                                    <select name="task-responsible" required class="select2" data-placeholder="Выберите ответственного...">
                                    <option value="">Выберите ответственного</option>
                                    <? 
                                    $users = getUsers($link);
                                    foreach ($users as $key => $value) { ?>
                                      <option value="<?=$users[$key]['login']?>"><?=$users[$key]['name']?></option>
                                    <? } ?>
                                    </select>
                                    <div class="help-block"></div>
                                </div>

                                <div class="form-group">
                                    <label>Соисполнители:</label>
                                    <select name="task-executors" required multiple class="select2" data-placeholder="Выберите соисполнителей...">
                                    <? 
                                    $users = getUsers($link);
                                    foreach ($users as $key => $value) { ?>
                                      <option value="<?=$users[$key]['login']?>"><?=$users[$key]['name']?></option>
                                    <? } ?>
                                    </select>
                                    <div class="help-block"></div>
                                </div>

                            </div>


                        </div>
                    </div>
                
                    <div class="modal-footer">

                      <div class="text-right">
                          <input type="button" class="btn btn-default" data-dismiss="modal" value="Отменить"/>
                          <input type="submit" class="btn btn-primary" value="Сохранить"/>
                      </div>

                    </form>
<div id="comments">
    <? include $_SERVER['DOCUMENT_ROOT']."/app/views/tasks/ajax-comments.php"; ?>
</div><!-- comments -->

                    </div>

            </div>
        </div>
    </div>
    </div>
<? } ?>


<!--СПЛЫВАЮЩЕЕ ОКНО(РЕДАКТИРОВАНИЕ КОММЕНТОВ В САМООЦЕНКАХ) -->
<div class="modal fade" id="EditComment">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">Редактор комментарии №<span class="comment_number"></span></h4>
      </div>
        <form class="save-comment" method="POST">
        <div class="modal-body">
            <div class="form-group">
                <textarea class="form-control vresize" id="comment" name="comment"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <div class="text-right">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Отменить" />
                <input type="button" class="btn btn-primary comment_but_save" value="Сохранить"  data-dismiss="modal"/>
            </div>
        </div>
        </form>
    </div>
  </div>
</div>


<? if ( strpos($_SERVER['REQUEST_URI'], 'register') !== false ) { ?>
<!-- ВСПЛЫВАЮЩЕЕ ОКНО(РЕДАКТИРОВАНИЕ ДОКУМЕНТА В РЕЕСТРЕ) -->
<div class="modal fade" id="editRegisterModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Редактировать документ:</h4>
      </div>  
      <form class="edit-task" method="POST">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">

              <input type="hidden" value="" name="register-action" />

              <div class="form-group">
                <label for="demand-name">Идентификатор документа в системе ТехЭксперт:<sup>*</sup></label>
                <input type="text" class="form-control" id="nd" name="register-nd" placeholder="">
                <div class="help-block"></div>
              </div>

              <div class="form-group">
                <label for="demand-name">Обозначение документа:<sup>*</sup></label>
                <input type="text" class="form-control" id="register-symbol" name="register-symbol" placeholder="">
                <div class="help-block"></div>
              </div>

              <div class="form-group">
                <label for="comment">Примечание к документу</label>
                <textarea class="form-control" rows="4" id="register-comment" name="register-comment"></textarea>
                <div class="help-block"></div>
              </div>

              <div class="form-group">
                <label>Группы документа:<sup>*</sup></label>
                <select name="register-group" required multiple class="select2" placeholder="Выберите группу документов" >
                  <? foreach ($docsGroup as $key => $value) { ?>
                    <option value="<?=$value;?>"><?=$value;?></option>
                  <? } ?>
                </select>
                <div class="help-block"></div>
              </div>
              
              <div class="form-group">
                <label>Используется на предприятии:<sup>*</sup></label>
                <div class="checkbox">
                  <label class="cr-styled">
                  <input type="checkbox" name="company-pepsico" value="pepsico" ><i class="fa"></i>PepsiCo, Inc</label>
                </div>

                <div class="checkbox">
                  <label class="cr-styled">
                  <input type="checkbox" name="company-gazprom" value="gazprom" ><i class="fa"></i>ООО «Газпром георесурс»</label>
                </div>
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
<? } ?>

<? if ( strpos($_SERVER['REQUEST_URI'], 'demand') !== false ) { ?>
<!-- ВСПЛЫВАЮЩЕЕ ОКНО(РЕДАКТОР ТРЕБОВАНИЙ) -->
<div class="modal fade" id="demandModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Добавить требования</h4>
      </div>  
      <form class="edit-demand" method="POST">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
                
                <input type="hidden" value="" name="demand-id" />

                <div class="form-group">
                  <label for="demand-name">Нормативный документ:<sup>*</sup></label>
                  <input type="text" class="form-control" name="demand-document" placeholder="">
                  <div class="help-block"></div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="demand-paragraph-name">Пункт документа:<sup>*</sup></label>
                      <input type="text" class="form-control" name="demand-paragraph-name" >
                      <div class="help-block"></div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="demand-paragraph-link">Ссылка на пункт документа:</label>
                      <input type="text" class="form-control" name="demand-paragraph-link" placeholder="kodeks://link/d?nd=9004157&prevdoc=901827804&point=mark=000000000007DE0K8">
                      <div class="help-block"></div>
                    </div>
                  </div>
                </div>

                <div id="demand-status" class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label >Статус документа:<sup>*</sup></label>
                      <select class="form-control" name='demand-status'>
                        <option value="На утверждении" selected>На утверждении</option>
                        <option value="Действующее">Действующее</option>
                        <option value="Недействующее">Недействующее</option>
                      </select>
                      <div class="help-block"></div>
                    </div>
                  </div>
                </div>             


                <div class="form-group">
                  <label for="demand-demand">Необходимые мероприятия:<sup>*</sup></label>
                  <textarea class="wysihtml5 form-control" rows="5" name="demand-demand"></textarea>
                  <div class="help-block"></div>
                </div>

                <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label >Критичность:<sup>*</sup></label>
                      <select class="form-control" name='demand-criticality1'>
                        <option value="0" disabled selected>OHSAS</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                      </select>
                      <div class="help-block"></div>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <label ></label>
                      <select class="form-control m-t-5" name='demand-criticality2'>
                        <option value="0" disabled selected>Влияние</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                      </select>
                      <div class="help-block"></div>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <label ></label>
                      <select class="form-control m-t-5" name='demand-criticality3'>
                        <option value="0" disabled selected>КоАП</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                      </select>
                      <div class="help-block"></div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="cr-styled m-t-30">
                        <input type="checkbox" name="demand-privilege" >
                        <i class="fa"></i> Привелигированное требование </label>
                    </div>
                  </div>
                </div>

                <hr>
                
                <div id="chains" class="row">
                  <div class="chain">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>EHS:</label>
                        <input type="text" class="form-control demand-ehs" name="demand-ehs-0" >
                        <div class="help-block"></div>
                      </div>
                    </div>
                    <div class="col-md-7">
                      <div class="form-group">
                        <label>Вид работы:</label>
                        <select class="form-control demand-job" name='demand-job-0' disabled="disabled">
                          <option value="" ></option>
                        </select>
                        <div class="help-block"></div>
                      </div>
                    </div>

                    <div class="col-md-1">
                      <div class="form-group m-t-30">
                        <a href="javascript:void(0);" class="remove-chunk" onclick="removeChain();"><i class="fa ion-close text-danger"></i></a>
                        <div class="help-block"></div>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                </div>

                <div class="row">
                  <input type="hidden" value="" name="demand-connected" />

                  <div class="col-md-12 text-center">
                    <a href="javascript:void(0);" class="add-chunk text-info" onclick="addChain();">Добавить еще одну связку</a>
                  </div>
                </div>

                <hr>

                <div class="form-group">
                  <label for="demand-event">Тип требования по срокам:<sup>*</sup></label>
                  <textarea class="form-control" rows="5" name="demand-event"></textarea>
                  <div class="help-block"></div>
                </div>

                <div class="form-group">
                  <label for="demand-source">Источник инфомации:<sup>*</sup></label>
                  <input type="text" class="form-control" name="demand-source" value='ИСС "Техэксперт"'>
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
<? } ?>

<? if ( strpos($_SERVER['REQUEST_URI'], 'demand') !== false ) { ?>

<? } ?>
<? if ( strpos($_SERVER['REQUEST_URI'], 'demand') !== false ) { ?>
<!-- ВСПЛЫВАЮЩЕЕ ОКНО(РЕДАКТОР СВЯЗЕЙ) -->
<div class="modal fade" id="editJOB">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Редактор видов работ</h4>
      </div>  
      <form id="job-list" method="POST" action="">

        <? include $_SERVER['DOCUMENT_ROOT']."/app/views/demand/ajax-job.php"; ?>

      </form>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
<? } ?>

<? if ( strpos($_SERVER['REQUEST_URI'], 'demand') !== false ) { ?>
<!-- ВСПЛЫВАЮЩЕЕ ОКНО(РЕДАКТОР СВЯЗЕЙ) -->
<div class="modal fade" id="editEHS">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Редактор EHS</h4>
      </div>  
      <form id="ehs-list" method="POST" action="">

        <? include $_SERVER['DOCUMENT_ROOT']."/app/views/demand/ajax-ehs.php"; ?>

      </form>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
<? } ?>

<? if ( strpos($_SERVER['REQUEST_URI'], 'reports') !== false ) { ?>
<!-- ВСПЛЫВАЮЩЕЕ ОКНО(РЕДАКТОР ТРЕБОВАНИЙ) -->
<div class="modal fade" id="editReport">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Подробная информация</h4>
      </div>  
      <form class="edit-report" method="POST">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
                
              <input type="hidden" value="" name="report-id" />

              <div class="form-group">
                <label>Аналитика:</label>
                <canvas id="report-chart" class="m-0-a" width="400" height="170"></canvas>
              </div>

              <div class="form-group">
                <label>Ответственный:</label>
                  <p id="report-responsible"></p>
              </div>

              <div class="form-group">
                <label>Виды работ:</label>
                  <p id="report-job"></p>
              </div>

              <div class="form-group">
                  <label>Единый статус соответствия:</label>
                  <select class="select2" name="single-tsatus" onchange="setSingleStatus(event);">
                      <option value="" selected >Выберите статус соответствия</option>
                      <option value="0" >Не заполнено</option>
                      <option value="5" >N/A</option>
                      <option value="1" >более 90% </option>
                      <option value="2" >от 71 до 90%</option>
                      <option value="3" >от 41 до 70%</option>
                      <option value="4" >менее 40%</option>
                  </select>
                  <div class="help-block"></div>
              </div>

              <!-- <div class="form-group">
                <label>Комментарий:</label>
                  <textarea class="form-control" name="report-comment" rows="5"></textarea>
                <div class="help-block"></div>
              </div> -->

            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="text-right">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Отменить" />
            <input type="button" class="btn btn-primary" onclick="editEvaluation();" value="Сохранить" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<? }*/ ?>