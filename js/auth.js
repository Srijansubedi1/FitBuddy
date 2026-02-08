console.log("auth.js loaded");

document.addEventListener('DOMContentLoaded', () => {

  // LOGIN BUTTON (if exists)
  const authBtn = document.getElementById('authBtn');
  if (authBtn) {
    authBtn.onclick = () => {
      window.location.href = 'login.php';
    };
  }

  // PROFILE DROPDOWN
  const profileBtn = document.querySelector('.profile-btn');
  const profileDropdown = document.querySelector('.member-dropdown');

  if (profileBtn && profileDropdown) {

    // Toggle dropdown
    profileBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      profileDropdown.classList.toggle('show');
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.profile-menu')) {
        profileDropdown.classList.remove('show');
      }
    });
  }

});
