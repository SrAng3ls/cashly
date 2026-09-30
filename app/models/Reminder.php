<?php
class Reminder {
    public static function all(int $uid): array { $st=Database::get()->prepare('SELECT * FROM reminders WHERE user_id=? ORDER BY event_date ASC'); $st->execute([$uid]); return $st->fetchAll(); }
    public static function create(int $uid,array $d): int { $st=Database::get()->prepare('INSERT INTO reminders(user_id,title,event_date,amount,kind,notes) VALUES(?,?,?,?,?,?)'); $st->execute([$uid,$d['title'],$d['event_date'],$d['amount'] ?: null,$d['kind'],$d['notes'] ?: null]); return (int)Database::get()->lastInsertId(); }
    public static function update(int $id,int $uid,array $d): void { $st=Database::get()->prepare('UPDATE reminders SET title=?,event_date=?,amount=?,kind=?,notes=? WHERE id=? AND user_id=?'); $st->execute([$d['title'],$d['event_date'],$d['amount'] ?: null,$d['kind'],$d['notes'] ?: null,$id,$uid]); }
    public static function delete(int $id,int $uid): void { $st=Database::get()->prepare('DELETE FROM reminders WHERE id=? AND user_id=?'); $st->execute([$id,$uid]); }
    public static function upcoming(int $uid,int $days=7): array { $st=Database::get()->prepare('SELECT * FROM reminders WHERE user_id=? AND event_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(),INTERVAL ? DAY) ORDER BY event_date'); $st->bindValue(1,$uid,PDO::PARAM_INT); $st->bindValue(2,$days,PDO::PARAM_INT); $st->execute(); return $st->fetchAll(); }
}
