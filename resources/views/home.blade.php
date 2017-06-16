@extends('layouts.app')

@section('content')
<div id="home-container" class="container">
    <div class="">
        <div class="col-xs-1"> <!-- required for floating -->
            <!-- Nav tabs -->
            <ul class="nav nav-tabs tabs-left">
              <li class="active">
                <a href="#home" data-toggle="tab">
                    <i class="fa fa-fw fa-2x fa-home"></i>
                </a>
              </li>
              <li>
                <a href="#clients" data-toggle="tab">
                    <i class="fa fa-fw fa-2x fa-windows"></i>
                </a>
              </li>
              <li>
                <a href="#exemptions" data-toggle="tab">
                    <i class="fa fa-fw fa-2x fa-check"></i>
                </a>
              </li>
              <li>
                <a href="#patterns" data-toggle="tab">
                    <i class="fa fa-fw fa-2x fa-cubes"></i>
                </a>
              </li>
              <li>
                <a href="#files" data-toggle="tab">
                    <i class="fa fa-fw fa-2x fa-bug"></i>
                </a>
              </li>
              <li>
                <a href="#logs" data-toggle="tab">
                    <i class="fa fa-fw fa-2x fa-file-text-o"></i>
                </a>
              </li>
              <li>
                <a href="#users" data-toggle="tab">
                    <i class="fa fa-fw fa-2x fa-users"></i>
                </a>
              </li>
            </ul>
        </div>

        <div class="col-xs-11">
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="home">Home</div>
                <div class="tab-pane" id="clients">
                    <div>
                        <clients></clients>
                    </div>
                </div>
                <div class="tab-pane" id="exemptions">
                    <div>
                        <exemptions></exemptions>
                    </div>
                </div>
                <div class="tab-pane" id="patterns">
                    <div>
                        <definitions></definitions>
                    </div>
                </div>
                <div class="tab-pane" id="files">Files</div>
                <div class="tab-pane" id="logs">Logs</div>
                <div class="tab-pane" id="users">Users</div>
            </div>
        </div>
    </div>
</div>
@endsection
