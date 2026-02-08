(function(){
  const KEY = 'fitbuddyUser'
  const rankOrder = { 'None': 0, 'Silver': 1, 'Gold': 2, 'Platinum': 3 }

  function getUser(){
    try { const raw = localStorage.getItem(KEY); return raw ? JSON.parse(raw) : { name: 'Guest', plan: 'None' } } catch(e){ return { name:'Guest', plan:'None' } }
  }

  function saveUser(user){ localStorage.setItem(KEY, JSON.stringify(user)) }

  function saveMembership(plan, name){
    const u = getUser()
    u.plan = plan
    if(name) u.name = name
    saveUser(u)
    showToast(`Membership active: ${plan}`)
    return u
  }

  function cancelMembership(){
    const u = getUser()
    u.plan = 'None'
    saveUser(u)
    showToast('Membership cancelled')
    return u
  }

  function requireMembership(minPlan){
    const u = getUser()
    const allowed = (rankOrder[u.plan] || 0) >= (rankOrder[minPlan] || 0)
    return { allowed, message: allowed ? 'Access granted' : `Access requires ${minPlan} membership or higher` }
  }

  function showMemberInfo(container){
    if(!container) return
    const u = getUser()
    container.innerHTML = ''
    const span = document.createElement('div')
    span.className = 'status'
    span.textContent = u.name && u.plan !== 'None' ? `${u.name} (${u.plan})` : u.plan
    container.appendChild(span)

    const btn = document.createElement('button')
    btn.className = 'small-btn'
    btn.textContent = u.plan === 'None' ? 'Get Plan' : 'Cancel'
    btn.addEventListener('click', ()=>{
      if(u.plan === 'None'){
        location.href = 'plans.php'
      } else {
        if(confirm('Cancel membership?')){
          cancelMembership()
          showMemberInfo(container)
        }
      }
    })
    container.appendChild(btn)
  }

  function showToast(msg, duration = 2200){
    const t = document.getElementById('toast')
    if(!t){ console.log('TOAST:', msg); return }
    t.textContent = msg
    t.classList.add('show')
    setTimeout(()=> t.classList.remove('show'), duration)
  }

  window.membership = { getUser, saveUser, saveMembership, cancelMembership, requireMembership, showMemberInfo, rankOrder, showToast }
})();
membership.loadFromServer = async function () {
  try {
    const res = await fetch("get_membership.php");
    const data = await res.json();

    if (data.status === "success") {
      membership.user = data.user;
    }
  } catch (err) {
    console.error("Membership load failed", err);
  }
};
