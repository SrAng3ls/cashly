<?php
class Budget {
    public static function all(int $uid): array { $st=Database::get()->prepare('SELECT b.*,COALESCE(SUM(e.amount),0) spent FROM budgets b LEFT JOIN budget_expenses e ON e.budget_id=b.id WHERE b.user_id=? GROUP BY b.id ORDER BY b.created_at DESC'); $st->execute([$uid]); return $st->fetchAll(); }
    public static function find(int $id,int $uid): ?array { $st=Database::get()->prepare('SELECT b.*,COALESCE(SUM(e.amount),0) spent FROM budgets b LEFT JOIN budget_expenses e ON e.budget_id=b.id WHERE b.id=? AND b.user_id=? GROUP BY b.id'); $st->execute([$id,$uid]); return $st->fetch() ?: null; }
    public static function create(int $uid,array $d): int { $st=Database::get()->prepare('INSERT INTO budgets(user_id,name,category,limit_amount,period,initial_spent) VALUES(?,?,?,?,?,?)'); $st->execute([$uid,$d['name'],$d['category'],$d['limit_amount'],$d['period'],$d['initial_spent']]); return (int)Database::get()->lastInsertId(); }
    public static function update(int $id,int $uid,array $d): void { $st=Database::get()->prepare('UPDATE budgets SET name=?,category=?,limit_amount=?,period=?,initial_spent=? WHERE id=? AND user_id=?'); $st->execute([$d['name'],$d['category'],$d['limit_amount'],$d['period'],$d['initial_spent'],$id,$uid]); }
    public static function delete(int $id,int $uid): void { $st=Database::get()->prepare('DELETE FROM budgets WHERE id=? AND user_id=?'); $st->execute([$id,$uid]); }
    public static function expenses(int $budget,int $uid): array { $st=Database::get()->prepare('SELECT e.* FROM budget_expenses e JOIN budgets b ON b.id=e.budget_id WHERE e.budget_id=? AND b.user_id=? ORDER BY e.date DESC,e.id DESC'); $st->execute([$budget,$uid]); return $st->fetchAll(); }
    public static function addExpense(int $budget,int $uid,array $d): void { $db=Database::get(); $db->beginTransaction(); $tx=$db->prepare("INSERT INTO transactions(user_id,type,amount,category,payment_method,date,description) SELECT ?, 'expense', ?, category, 'Presupuesto', ?, ? FROM budgets WHERE id=? AND user_id=?"); $tx->execute([$uid,$d['amount'],$d['date'],$d['description'] ?: 'Gasto de presupuesto',$budget,$uid]); $transactionId=(int)$db->lastInsertId(); $st=$db->prepare('INSERT INTO budget_expenses(budget_id,transaction_id,amount,date,description) SELECT ?,?,?,?,? FROM budgets WHERE id=? AND user_id=?'); $st->execute([$budget,$transactionId,$d['amount'],$d['date'],$d['description'] ?: null,$budget,$uid]); $db->commit(); }
    public static function updateExpense(int $id,int $uid,array $d): void {
        $db=Database::get();
        $st=$db->prepare('SELECT e.transaction_id FROM budget_expenses e JOIN budgets b ON b.id=e.budget_id WHERE e.id=? AND b.user_id=?');
        $st->execute([$id,$uid]); $row=$st->fetch(); if(!$row)return;
        $st=$db->prepare('UPDATE budget_expenses e JOIN budgets b ON b.id=e.budget_id SET e.amount=?,e.date=?,e.description=? WHERE e.id=? AND b.user_id=?');
        $st->execute([$d['amount'],$d['date'],$d['description'] ?: null,$id,$uid]);
        if(!empty($row['transaction_id'])) { $x=$db->prepare("UPDATE transactions SET amount=?,date=?,description=? WHERE id=? AND user_id=?"); $x->execute([$d['amount'],$d['date'],$d['description'] ?: 'Gasto de presupuesto',(int)$row['transaction_id'],$uid]); }
    }
    public static function deleteExpense(int $id,int $uid): void {
        $db=Database::get(); $db->beginTransaction();
        $st=$db->prepare('SELECT e.transaction_id FROM budget_expenses e JOIN budgets b ON b.id=e.budget_id WHERE e.id=? AND b.user_id=?'); $st->execute([$id,$uid]); $row=$st->fetch(); if(!$row){$db->rollBack();return;}
        $st=$db->prepare('DELETE e FROM budget_expenses e JOIN budgets b ON b.id=e.budget_id WHERE e.id=? AND b.user_id=?'); $st->execute([$id,$uid]);
        if(!empty($row['transaction_id'])){$x=$db->prepare('DELETE FROM transactions WHERE id=? AND user_id=?');$x->execute([(int)$row['transaction_id'],$uid]);}
        $db->commit();
    }
}
