document.addEventListener('DOMContentLoaded', ()=>{
  // Populate member header (supports both new #memberHeader or legacy #memberInfo)
  const topHeader = document.getElementById('memberHeader') || document.getElementById('memberInfo')
  if(window.membership && topHeader) membership.showMemberInfo(topHeader)

  // Plan card animations
  const allCards = document.querySelectorAll('.plan-card, .feature-card')
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches
  if(!reduce){ allCards.forEach((c,i)=>{ c.classList.add('card-anim'); setTimeout(()=> c.classList.add('in'), i*80) }) } else { allCards.forEach(c=> c.classList.add('in')) }

  // Plan card interactions
  const planCards = document.querySelectorAll('.plan-card')
  planCards.forEach(card => {
    const select = card.querySelector('.select-plan')
    const inline = card.querySelector('.purchase-inline')
    const confirm = card.querySelector('.confirm-inline')
    const cancel = card.querySelector('.cancel-inline')
    const nameInput = card.querySelector('.name-input')

    if(select) select.addEventListener('click', ()=>{
      document.querySelectorAll('.purchase-inline.active').forEach(p=>{ p.classList.remove('active'); p.setAttribute('aria-hidden','true') })
      inline.classList.toggle('active')
      inline.setAttribute('aria-hidden', !inline.classList.contains('active'))
      if(inline.classList.contains('active') && nameInput) nameInput.focus()
    })

    if(confirm) confirm.addEventListener('click', ()=>{
      const plan = card.dataset.plan
      const name = nameInput ? nameInput.value.trim() : ''
      if(window.membership){
        membership.saveMembership(plan, name)
        membership.showMemberInfo(topHeader || document.getElementById('memberInfo'))
        membership.showToast(`Welcome ${name || 'member'} — ${plan} active`)
      }
      inline.classList.remove('active')
      inline.setAttribute('aria-hidden','true')
    })

    if(cancel) cancel.addEventListener('click', ()=>{
      inline.classList.remove('active')
      inline.setAttribute('aria-hidden','true')
    })
  })

 
})