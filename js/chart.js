
fetch('php/result.php')
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
