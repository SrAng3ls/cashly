<?php
class Transaction {
    public static function summary(int $uid): array {
        $st=Database::get()->prepare("SELECT COALESCE(SUM(CASE WHEN type='income' THEN amount ELSE 0 END),0) income, COALESCE(SUM(CASE WHEN type='expense' THEN amount ELSE 0 END),0) expense FROM transactions WHERE user_id=?"); $st->execute([$uid]); $r=$st->fetch(); $r['balance']=(float)$r['income']-(float)$r['expense']; return $r;
    }
    public static function last7(int $uid): array {
        $st=Database::get()->prepare("SELECT DATE(date) day, SUM(CASE WHEN type='income' THEN amount ELSE 0 END) income, SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) expense FROM transactions WHERE user_id=? AND date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY DATE(date) ORDER BY day"); $st->execute([$uid]); return $st->fetchAll();
    }
    public static function categoryTotals(int $uid,string $type): array { $st=Database::get()->prepare('SELECT category, SUM(amount) total FROM transactions WHERE user_id=? AND type=? GROUP BY category ORDER BY total DESC'); $st->execute([$uid,$type]); return $st->fetchAll(); }
    public static function all(int $uid,int $limit=7,int $offset=0): array { $st=Database::get()->prepare('SELECT * FROM transactions WHERE user_id=? ORDER BY date DESC,id DESC LIMIT ? OFFSET ?'); $st->bindValue(1,$uid,PDO::PARAM_INT); $st->bindValue(2,$limit,PDO::PARAM_INT); $st->bindValue(3,$offset,PDO::PARAM_INT); $st->execute(); return $st->fetchAll(); }
    public static function count(int $uid): int { $st=Database::get()->prepare('SELECT COUNT(*) c FROM transactions WHERE user_id=?'); $st->execute([$uid]); return (int)$st->fetch()['c']; }
    public static function find(int $id,int $uid): ?array { $st=Database::get()->prepare('SELECT * FROM transactions WHERE id=? AND user_id=?'); $st->execute([$id,$uid]); return $st->fetch() ?: null; }
    public static function create(int $uid,array $d): int { $st=Database::get()->prepare('INSERT INTO transactions(user_id,type,amount,category,payment_method,date,description) VALUES(?,?,?,?,?,?,?)'); $st->execute([$uid,$d['type'],$d['amount'],$d['category'],$d['payment_method'],$d['date'],$d['description'] ?: null]); return (int)Database::get()->lastInsertId(); }
    public static function update(int $id,int $uid,array $d): void { $st=Database::get()->prepare('UPDATE transactions SET type=?,amount=?,category=?,payment_method=?,date=?,description=? WHERE id=? AND user_id=?'); $st->execute([$d['type'],$d['amount'],$d['category'],$d['payment_method'],$d['date'],$d['description'] ?: null,$id,$uid]); }
    public static function delete(int $id,int $uid): void { $st=Database::get()->prepare('DELETE FROM transactions WHERE id=? AND user_id=?'); $st->execute([$id,$uid]); }
    public static function calendar(int $uid,string $from,string $to): array { $st=Database::get()->prepare('SELECT * FROM transactions WHERE user_id=? AND date BETWEEN ? AND ? ORDER BY date ASC,id ASC'); $st->execute([$uid,$from,$to]); return $st->fetchAll(); }
    public static function peakDays(int $uid): array { $st=Database::get()->prepare("SELECT DATE(date) day, SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) expenses, SUM(CASE WHEN type='income' THEN amount ELSE 0 END) incomes FROM transactions WHERE user_id=? GROUP BY DATE(date) ORDER BY expenses DESC LIMIT 5"); $st->execute([$uid]); return $st->fetchAll(); }
}
