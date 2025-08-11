fetch('php/result.php')

const votesCtx = document.getElementById('votesChart').getContext('2d');
new Chart(votesCtx, {
  type: 'bar',
  data: {
    labels: candidateLabels,
    datasets: [{
      label: 'Votes',
      data: candidateVotes,
      backgroundColor: '#4CAF50'
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false
  }
});

const rolesCtx = document.getElementById('rolesChart').getContext('2d');
new Chart(rolesCtx, {
  type: 'pie',
  data: {
    labels: roleLabels,
    datasets: [{
      data: roleCounts,
      backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false
  }
});


  .then(res => res.json())
  .then(data => {
    const ctx = document.getElementById('resultsChart').getContext('2d');
    const labels = data.map(item => item.party);
    const votes = data.map(item => item.total_votes);

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Votes',
          data: votes,
          backgroundColor: 'rgba(54, 162, 235, 0.5)',
        }]
      }
    });
  });
