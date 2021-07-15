<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<!-- Top Bar Start -->
<div class="topbar">

    <nav class="navbar-custom">

        <ul class="list-inline float-right mb-0">
            <li class="list-inline-item dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="ti-bell noti-icon"></i>
                    <span class="badge badge-success noti-icon-badge">23</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg">
                    <div class="dropdown-item noti-title">
                        <h5><span class="badge badge-danger float-right">87</span>Notification</h5>
                    </div>

                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        View All
                    </a>

                </div>
            </li>

            <li class="list-inline-item dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    <?= Html::img( Yii::getAlias("@file/") .Yii::$app->user->identity->photo_url, ["class" => "rounded-circle"]) ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5>Welcome</h5>
                    </div>
                    <?= Html::a(
                                    '<i class="mdi mdi-account-circle m-r-5 text-muted"></i> Profile',
                                    ['site/profile'],
                                    ['class' => 'dropdown-item']
                                ) ?>
                    <div class="dropdown-divider"></div>
                        <?= Html::a(
                                    '<i class="mdi mdi-logout m-r-5 text-muted"></i> Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'dropdown-item', 'child']
                                ) ?>
                </div>
            </li>

        </ul>

        <ul class="list-inline menu-left mb-0">
            <li class="float-left">
                <button class="button-menu-mobile open-left waves-light waves-effect">
                    <i class="mdi mdi-menu"></i>
                </button>
            </li>
            <!-- <li class="hide-phone app-search">
                <form role="search" class="">
                    <input type="text" placeholder="Search..." class="form-control">
                    <a href=""><i class="fa fa-search"></i></a>
                </form>
            </li> -->
        </ul>

        <div class="clearfix"></div>

    </nav>

</div>
<!-- Top Bar End -->