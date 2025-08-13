fetch('chart.php')
  .then(response => response.json())
  .then(data => {
    const votesChart = new Chart(document.getElementById('votesChart').getContext('2d'), {
      type: 'bar',
      data: {
        labels: data.names,
        datasets: [{
          label: 'Votes',
          data: data.votes,
          backgroundColor: '#4CAF50'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false
      }
    });

    const rolesChart = new Chart(document.getElementById('rolesChart').getContext('2d'), {
      type: 'pie',
      data: {
        labels: data.roles.labels,
        datasets: [{
          data: data.roles.counts,
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false
      }
    });
  })
  .catch(console.error);
