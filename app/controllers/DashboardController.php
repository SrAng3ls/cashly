<?php
class DashboardController extends Controller {
    public function index(): void { Auth::requireLogin(); $uid=Auth::user()['id']; $this->render('dashboard/index',['summary'=>Transaction::summary($uid),'last7'=>Transaction::last7($uid),'incomeCats'=>Transaction::categoryTotals($uid,'income'),'expenseCats'=>Transaction::categoryTotals($uid,'expense'),'transactions'=>Transaction::all($uid,7,0),'txCount'=>Transaction::count($uid),'upcoming'=>Reminder::upcoming($uid)]); }
}
