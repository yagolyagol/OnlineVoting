//dynamic candidate loader
function loadCandidates() {
  const select = document.getElementById('candidateSelect');
  if (!select) return;

  fetch('php/list_candidates.php')
    .then(res => res.json())
    .then(data => {
      select.innerHTML = '';
      if (data.length === 0) {
        const option = document.createElement('option');
        option.text = 'No candidates available';
        select.add(option);
        return;
      }
      data.forEach(c => {
        const option = document.createElement('option');
        option.value = c.candidate_id;
        option.text = `${c.party} - ${c.position}`;
        select.add(option);
      });
    })
    .catch(() => {
      select.innerHTML = '';
      const option = document.createElement('option');
      option.text = 'Failed to load candidates';
      select.add(option);
    });
}

//sample form validation and message display
function showMessage(containerId, message, isSuccess = true) {
  const container = document.getElementById(containerId);
  if (!container) return;

  container.innerHTML = message;
  container.className = 'message ' + (isSuccess ? 'success' : 'error');
  setTimeout(() => container.innerHTML = '', 5000);
}

// Example: validate registration form
function validateRegisterForm() {
  const name = document.getElementById('fullName').value.trim();
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;

  if (name.length < 3) {
    showMessage('regMsg', 'Full name must be at least 3 characters', false);
    return false;
  }
  if (!email.includes('@')) {
    showMessage('regMsg', 'Enter a valid email', false);
    return false;
  }
  if (password.length < 6) {
    showMessage('regMsg', 'Password must be at least 6 characters', false);
    return false;
  }
  return true;
}
