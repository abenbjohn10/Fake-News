document.getElementById('loginForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent form submission
  
    // Get input values
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
  
    // Check credentials
    if (username === 'admin' && password === '12345') {
      // Redirect to welcome.html
      window.location.href = 'welcome.html';
    } else {
      alert('Invalid username or password. Please try again.');
    }
  });