(function(){
const money=(v,c)=>v===null||v===undefined?'—':new Intl.NumberFormat('pt-BR',{style:'currency',currency:c||'USD',maximumFractionDigits:2}).format(Number(v));
const pct=v=>v===null||v===undefined?'—':`${Number(v)>0?'+':''}${Number(v).toFixed(2)}%`;
const cls=v=>Number(v)>0?'positive':Number(v)<0?'negative':'text-secondary';
let allItems=[];
function track(event,target){fetch('api/track.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({event,target})}).catch(()=>{});}
document.addEventListener('click',e=>{const el=e.target.closest('[data-track]');if(el)track('click',el.getAttribute('data-track'));});
async function loadMarket(){
 try{
  const res=await fetch('api/market.php',{cache:'no-store'});const data=await res.json();if(!data.ok)throw new Error('API');
  allItems=data.items||[];renderDashboard(allItems);renderRadar(allItems);
  document.getElementById('lastUpdate').textContent=new Date(data.updated_at).toLocaleTimeString('pt-BR');
 }catch(e){const body=document.getElementById('marketRows');if(body)body.innerHTML='<tr><td colspan="5" class="text-danger">Falha ao carregar. Verifique a configuração da API e do banco.</td></tr>';}
}
function renderDashboard(items){
 const valid=items.filter(x=>!x.demo&&x.change!==null&&x.change!==undefined);
 const drops=valid.filter(x=>Number(x.change)<=-3),ups=valid.filter(x=>Number(x.change)>0);
 const el=id=>document.getElementById(id);
 if(el('assetCount'))el('assetCount').textContent=items.length;
 if(el('dropCount'))el('dropCount').textContent=drops.length;
 if(el('upCount'))el('upCount').textContent=ups.length;
 const rows=el('marketRows');
 if(rows)rows.innerHTML=items.map(x=>`<tr><td><strong>${esc(x.symbol)}</strong><div class="small-muted">${esc(x.name)}</div></td><td><span class="badge badge-soft">${esc(x.market)}</span></td><td>${money(x.price,x.currency)}</td><td class="${cls(x.change)} fw-semibold">${pct(x.change)}</td><td>${x.demo?`<span class="badge text-bg-secondary">${esc(x.source||'Sem API')}</span>`:'<span class="badge text-bg-success">Dado recebido</span>'}</td></tr>`).join('');
 const cards=el('dropCards');
 if(cards){const top=drops.sort((a,b)=>a.change-b.change).slice(0,4);cards.innerHTML=top.length?top.map(x=>`<div class="col-sm-6 col-xl-3"><div class="market-card"><div class="small-muted">${esc(x.market)}</div><div class="fw-bold mt-1">${esc(x.symbol)}</div><div class="small-muted">${esc(x.name)}</div><div class="h4 negative mt-3 mb-0">${pct(x.change)}</div><div class="small-muted mt-2">${esc(x.reading)}</div></div></div>`).join(''):'<div class="col-12 text-secondary small">Nenhuma queda relevante com dados válidos nesta consulta.</div>';}
 const canvas=el('movementChart');
 if(canvas&&window.Chart){if(window.xyzChart)window.xyzChart.destroy();window.xyzChart=new Chart(canvas,{type:'doughnut',data:{labels:['Quedas ≥ 3%','Altas','Demais'],datasets:[{data:[drops.length,ups.length,Math.max(0,valid.length-drops.length-ups.length)],backgroundColor:['#ff6577','#20c997','#394454'],borderWidth:0}]},options:{maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{color:'#aab4c1',usePointStyle:true}}}}});}
}
function renderRadar(items){
 const body=document.getElementById('radarRows');if(!body)return;
 const search=(document.getElementById('filterSearch')?.value||'').toLowerCase();
 const market=document.getElementById('filterMarket')?.value||'';
 const threshold=Number(document.getElementById('filterDrop')?.value??-3);
 const filtered=items.filter(x=>!x.demo&&x.change!==null&&Number(x.change)<0&&Number(x.change)<=threshold&&(!market||x.market===market)&&(`${x.symbol} ${x.name}`.toLowerCase().includes(search))).sort((a,b)=>a.change-b.change);
 body.innerHTML=filtered.length?filtered.map(x=>`<tr><td><strong>${esc(x.symbol)}</strong><div class="small-muted">${esc(x.name)}</div></td><td>${esc(x.market)}</td><td>${money(x.price,x.currency)}</td><td class="negative fw-bold">${pct(x.change)}</td><td>${esc(x.reading)}</td></tr>`).join(''):'<tr><td colspan="5" class="text-secondary py-4">Nenhum ativo atende aos filtros com dados válidos.</td></tr>';
}
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));}
document.getElementById('applyFilters')?.addEventListener('click',()=>{track('search','radar-filters');renderRadar(allItems);});
document.getElementById('filterSearch')?.addEventListener('keydown',e=>{if(e.key==='Enter'){track('search','radar-search');renderRadar(allItems);}});
if(document.getElementById('marketRows')||document.getElementById('radarRows'))loadMarket();
})();
