document.addEventListener('DOMContentLoaded', () => {

  /* ================= HERO IMAGE ================= */
  const heroImg = document.querySelector('.hero-image img')
  if (heroImg) heroImg.src = 'images/total-shape-wXBK9JrM0iU-unsplash.jpg'


  /* ================= WINNERS SECTION ================= */
  const winners = document.querySelectorAll('.winner')
  const winnersImgs = [
    'images/anastase-maragos-7kEpUPB8vNk-unsplash.jpg',
    'images/anastase-maragos-9dzWZQWZMdE-unsplash.jpg',
    'images/danielle-cerullo-CQfNt66ttZM-unsplash.jpg',
    'images/edgar-chaparro-sHfo3WOgGTU-unsplash.jpg',
    'images/hayley-kim-studios-eot-ka5dM7Q-unsplash.jpg',
    'images/sushil-ghimire-5UbIqV58CW8-unsplash.jpg'
  ]

  winners.forEach((w, i) => {
    const img = w.querySelector('img')
    if (img) img.src = winnersImgs[i % winnersImgs.length]

    // Confetti effect
    const conf = w.querySelector('.confetti')
    if (conf) {
      const colors = ['#ffcf3f', '#ff6b6b', '#7bf1a8', '#6ec6ff', '#d9a7ff']
      for (let k = 0; k < 8; k++) {
        const sp = document.createElement('span')
        sp.className = 'confetti-piece'
        sp.style.left = (Math.random() * 90 + 5) + '%'
        sp.style.background = colors[Math.floor(Math.random() * colors.length)]
        sp.style.animationDelay = (Math.random() * 0.9) + 's'
        sp.style.opacity = 0
        conf.appendChild(sp)
      }
    }

    // Entrance animation
    w.classList.add('card-anim')
    setTimeout(() => {
      w.classList.add('in')

      const badge = w.querySelector('.badge')
      if (badge) setTimeout(() => badge.classList.add('pop'), 120)

      const pieces = w.querySelectorAll('.confetti-piece')
      pieces.forEach((p, idx) => p.style.animationDelay = (idx * 0.06) + 's')

    }, i * 140)
  })

  // Hover sparkle
  document.querySelectorAll('.winner').forEach(w => {
    w.addEventListener('mouseenter', () => {
      const pieces = w.querySelectorAll('.confetti-piece')
      pieces.forEach((p, idx) => {
        p.style.animation = 'confettiFly .9s ease forwards'
        p.style.opacity = 1
        p.style.animationDelay = (Math.random() * 0.3) + 's'
      })
    })
  })


  /* ================= ABOUT STATS (FIXED) ================= */

  const stats = document.querySelectorAll('.about-stats .stat')

  stats.forEach((stat, idx) => {
    const numEl = stat.querySelector('.stat-number')

    // Read value printed by PHP
    const target = parseInt(numEl.textContent.replace(/,/g, ''), 10) || 0

    const duration = 1000
    const delay = idx * 150
    let startTime = null

    // Reset to zero before animation
    numEl.textContent = "0"

    function animate(now) {
      if (!startTime) startTime = now
      const elapsed = now - startTime - delay

      if (elapsed < 0) {
        requestAnimationFrame(animate)
        return
      }

      const progress = Math.min(elapsed / duration, 1)
      const value = Math.floor(progress * target)

      numEl.textContent = value.toLocaleString()

      if (progress < 1) {
        requestAnimationFrame(animate)
      } else {
        numEl.textContent = target.toLocaleString()
      }
    }

    requestAnimationFrame(animate)
  })


  /* ================= TEAM SECTION ================= */

  const teamImgs = [
    'images/tyler-raye-gnJqUTCPzzg-unsplash.jpg',
    'images/danielle-cerullo-CQfNt66ttZM-unsplash.jpg',
    'images/edgar-chaparro-sHfo3WOgGTU-unsplash.jpg',
    'images/hayley-kim-studios-eot-ka5dM7Q-unsplash.jpg'
  ]

  const teams = document.querySelectorAll('.team-card')

  teams.forEach((card, i) => {
    const img = card.querySelector('img')
    if (img) {
      img.src = teamImgs[i % teamImgs.length]
      img.alt = img.alt || `Coach ${i + 1}`
    }

    card.classList.add('card-anim')
    setTimeout(() => card.classList.add('in'), 500 + i * 120)

    card.addEventListener('mouseenter', () => {
      if (img) img.style.transform = 'scale(1.04)'
    })

    card.addEventListener('mouseleave', () => {
      if (img) img.style.transform = ''
    })
  })

})
