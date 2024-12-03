document.addEventListener('DOMContentLoaded', function () {
    // Fetch leaderboard data
    fetch('fetch_leaderboard.php')
        .then(response => response.json())
        .then(data => {
            const leaderboardBody = document.getElementById('leaderboard-body');
            leaderboardBody.innerHTML = ''; // Clear existing content

            // Populate leaderboard rows
            data.forEach(entry => {
                const row = document.createElement('tr');

                // Rank column
                const rankCell = document.createElement('td');
                rankCell.textContent = entry.rank;
                row.appendChild(rankCell);

                // Name column
                const nameCell = document.createElement('td');
                nameCell.textContent = entry.name;
                row.appendChild(nameCell);

                // Score column
                const scoreCell = document.createElement('td');
                scoreCell.textContent = `${entry.score} points`;
                row.appendChild(scoreCell);

                // Append row to the table
                leaderboardBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching leaderboard data:', error));
});
