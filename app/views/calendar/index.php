<?php
$pageTitle='Calendario';
$first=strtotime($month.'-01');
$days=(int)date('t',$first);
$start=(int)date('N',$first);
$byDay=[];
foreach($transactions as $t) $byDay[(int)date('j',strtotime($t['date']))][]=$t;
$monthName=date('F Y',$first);
$prev=date('Y-m',strtotime($month.'-01 -1 month'));
$next=date('Y-m',strtotime($month.'-01 +1 month'));
?>
<section class="page-head"><div><p class="eyebrow">Actividad financiera</p><h1>Calendario</h1><p class="muted">Consulta tus transacciones y fechas importantes.</p></div></section>
<div class="calendar-layout">
<div class="panel calendar-panel">
<div class="calendar-nav"><a href="index.php?url=calendar&month=<?=$prev?>">‹</a><h2><?=ucfirst($monthName)?></h2><a href="index.php?url=calendar&month=<?=$next?>">›</a></div>
<div class="weekdays"><span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span><span>Dom</span></div>
<div class="calendar-grid">
<?php for($i=1;$i<$start;$i++): ?><div class="day muted-day"></div><?php endfor; ?>
<?php for($d=1;$d<=$days;$d++): ?>
<div class="day"><strong><?=$d?></strong>
<?php foreach(($byDay[$d]??[]) as $t): ?><span class="event <?=$t['type']?>"><?=htmlspecialchars($t['category'])?> $<?=number_format($t['amount'],0,',','.')?></span><?php endforeach; ?>
<?php foreach($reminders as $r): if((int)date('j',strtotime($r['event_date']))===$d && date('Y-m',strtotime($r['event_date']))===$month): ?><span class="event reminder">🔔 <?=htmlspecialchars($r['title'])?></span><?php endif; endforeach; ?>
</div>
<?php endfor; ?>
</div></div>
<div class="side-stack">
<div class="panel"><div class="panel-title"><h2>Registrar fecha importante</h2></div><form class="form-grid" method="post" action="index.php?url=reminder/save"><input type="hidden" name="csrf" value="<?=$csrf?>"><label>Nombre<input name="title" placeholder="Spotify, servicios..." required></label><label>Fecha<input type="date" name="event_date" required></label><label>Monto (opcional)<input type="number" name="amount" min="0"></label><label>Tipo<select name="kind"><option value="payment">Pago</option><option value="subscription">Suscripción</option><option value="other">Otro</option></select></label><label class="span-2">Notas<textarea name="notes" rows="2"></textarea></label><button class="btn primary span-2">Crear recordatorio</button></form></div>
<div class="panel"><div class="panel-title"><h2>Próximas fechas</h2><span>7 días</span></div>
<?php foreach($reminders as $r): $diff=(int)floor((strtotime($r['event_date'])-strtotime(date('Y-m-d')))/86400); ?><div class="reminder-row"><div><strong><?=htmlspecialchars($r['title'])?></strong><small><?=date('d/m/Y',strtotime($r['event_date']))?> · <?=$diff===0?'Hoy':'en '.$diff.' días'?></small></div><a class="text-edit" href="index.php?url=reminder/edit&id=<?=$r['id']?>">Editar</a><form method="post" action="index.php?url=reminder/delete"><input type="hidden" name="csrf" value="<?=$csrf?>"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="text-danger">Eliminar</button></form></div><?php endforeach; if(!$reminders): ?><p class="muted">No hay pagos próximos.</p><?php endif; ?></div>
<div class="panel"><div class="panel-title"><h2>Días destacados</h2></div><?php foreach($peaks as $p): ?><div class="peak"><span><?=date('d/m/Y',strtotime($p['day']))?></span><span>Gastos $<?=number_format($p['expenses'],0,',','.')?></span><span>Ingresos $<?=number_format($p['incomes'],0,',','.')?></span></div><?php endforeach; if(!$peaks): ?><p class="muted">Aún no hay suficientes movimientos para mostrar días destacados.</p><?php endif; ?></div>
</div></div>
