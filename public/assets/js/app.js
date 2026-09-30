function toggleModal(id){const m=document.getElementById(id);if(m)m.classList.toggle('show')}
document.addEventListener('DOMContentLoaded',()=>{
 const c=window.cashlyCharts;
 if(c && window.Chart){
  const line=document.getElementById('weeklyChart'); if(line)new Chart(line,{type:'line',data:{labels:c.weekly.labels,datasets:[{label:'Ingresos',data:c.weekly.income,borderWidth:3,tension:.35,fill:false},{label:'Gastos',data:c.weekly.expense,borderWidth:3,tension:.35,fill:false}]},options:{responsive:true,plugins:{legend:{position:'top'}},scales:{y:{beginAtZero:true,ticks:{callback:v=>'$'+Number(v).toLocaleString('es-CO')}}}}});
  const mk=(id,data)=>{const el=document.getElementById(id);if(el)new Chart(el,{type:'doughnut',data:{labels:data.labels.length?data.labels:['Sin datos'],datasets:[{data:data.data.length?data.data:[1],borderWidth:3}]},options:{cutout:'68%',plugins:{legend:{position:'bottom'}}}})};
  mk('incomeChart',c.income);mk('expenseChart',c.expense);
 }
});
