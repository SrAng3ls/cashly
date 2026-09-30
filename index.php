<?php
declare(strict_types=1);
require_once __DIR__.'/app/config/config.php';
foreach (glob(__DIR__.'/app/core/*.php') as $f) require_once $f;
foreach (glob(__DIR__.'/app/models/*.php') as $f) require_once $f;
foreach (glob(__DIR__.'/app/controllers/*.php') as $f) require_once $f;
$url=trim($_GET['url']??'login','/');
$routes=[
 'login'=>[AuthController::class,'login'],'register'=>[AuthController::class,'register'],'forgot'=>[AuthController::class,'forgot'],'reset'=>[AuthController::class,'reset'],'logout'=>[AuthController::class,'logout'],
 'dashboard'=>[DashboardController::class,'index'],'transactions'=>[TransactionController::class,'index'],'transaction/save'=>[TransactionController::class,'save'],'transaction/edit'=>[TransactionController::class,'edit'],'transaction/delete'=>[TransactionController::class,'delete'],
 'goals'=>[GoalController::class,'index'],'goal/save'=>[GoalController::class,'save'],'goal/edit'=>[GoalController::class,'edit'],'goal/delete'=>[GoalController::class,'delete'],'goal/contribution'=>[GoalController::class,'contribution'],'goal/editContribution'=>[GoalController::class,'editContribution'],'goal/deleteContribution'=>[GoalController::class,'deleteContribution'],
 'budgets'=>[BudgetController::class,'index'],'budget/save'=>[BudgetController::class,'save'],'budget/edit'=>[BudgetController::class,'edit'],'budget/delete'=>[BudgetController::class,'delete'],'budget/expense'=>[BudgetController::class,'expense'],'budget/editExpense'=>[BudgetController::class,'editExpense'],'budget/deleteExpense'=>[BudgetController::class,'deleteExpense'],
 'calendar'=>[CalendarController::class,'index'],'reminder/save'=>[CalendarController::class,'reminderSave'],'reminder/edit'=>[CalendarController::class,'reminderEdit'],'reminder/delete'=>[CalendarController::class,'reminderDelete'],
 'admin'=>[AdminController::class,'index'],'admin/save'=>[AdminController::class,'save'],'admin/delete'=>[AdminController::class,'delete']
];
if(!isset($routes[$url])){$url=Auth::check()?'dashboard':'login';}
[$class,$method]=$routes[$url];(new $class())->$method();
