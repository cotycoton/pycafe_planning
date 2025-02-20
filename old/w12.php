


CTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Table with Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 8px;
        }
        .highlight {
            background-color: #f0f8ff;
        }
        .special-cell {
            background-color: #ffe4b5;
        }
        .today {
            background-color: #90ee90;
        }
        .current-user {
            font-weight: bold;
            color: #007bff;
            background-color: #e7f3ff;
            padding: 2px 4px;
            border-radius: 4px;
        }
        .hidden-button {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <button id="prev-week" class="btn btn-primary">&larr; Previous Week</button>
            <button id="next-week" class="btn btn-primary">Next Week &rarr;</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <!-- Days of the week will be dynamically populated here -->
                </tr>
            </thead>
            <tbody>
                <!-- Timeslots and cells will be dynamically populated here -->
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const currentUser = 'user1';
            const users = ['user1', 'user2', 'user3', 'user4', 'user5', 'user6', 'user7', 'user8', 'user9', 'user10'];

            const daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            const timeslots = ['8:10-10:30', '10:30-12:30', '', '15:30-17:30', '17:30-19:30'];

            const tableHead = document.querySelector('thead tr');
            const tableBody = document.querySelector('tbody');

            // Populate table headers
            tableHead.innerHTML = '<th></th>' + daysOfWeek.map(day => `<th>${day}</th>`).join('');

            // Populate table rows
            timeslots.forEach((slot, rowIndex) => {
                const row = document.createElement('tr');

                if (slot === '') {
                    row.innerHTML = '<td colspan="8"></td>';
                } else {
                    row.innerHTML = `<td>${slot}</td>` + daysOfWeek.map((day, colIndex) => {
                        const isSpecialCell = (day === 'Monday' && (rowIndex === 3 || rowIndex === 4)) ||
                                              (day === 'Wednesday') ||
                                              (day === 'Friday' && (rowIndex === 3 || rowIndex === 4)) ||
                                              (day === 'Saturday' && (rowIndex === 1 || rowIndex === 2));

                        const randomUsers = users.sort(() => 0.5 - Math.random()).slice(0, 3);
                        const userList = randomUsers.map(user => {
                            const isCurrentUser = user === currentUser;
                            return `<div class="${isCurrentUser ? 'current-user' : ''}">${user}${isCurrentUser ? ` <button class='btn btn-sm btn-danger remove-user hidden-button'><i class='bi bi-trash'></i></button>` : ''}</div>`;
                        }).join('');

                        return `<td class="${isSpecialCell ? 'special-cell' : ''}" data-day="${day}" data-slot="${slot}">${userList}</td>`;
                    }).join('');
                }

                tableBody.appendChild(row);
            });

            // Highlight today's date
            const today = new Date();
            const todayDay = daysOfWeek[today.getDay() - 1]; // Adjust to match index

            if (todayDay) {
                const todayHeader = Array.from(tableHead.querySelectorAll('th')).find(th => th.textContent === todayDay);
                if (todayHeader) {
                    todayHeader.classList.add('today');
                }
            }

            // Cell selection logic
            let selectedCell = null;

            tableBody.addEventListener('click', (e) => {
                const cell = e.target.closest('td');
                if (!cell || !cell.dataset.day || !cell.dataset.slot) return;

                if (selectedCell) {
                    selectedCell.classList.remove('highlight');
                    const trashButtons = selectedCell.querySelectorAll('.remove-user');
                    trashButtons.forEach(button => button.classList.add('hidden-button'));
                }

                selectedCell = cell;
                selectedCell.classList.add('highlight');

                // Show trash button only if the cell is highlighted
                const trashButtons = selectedCell.querySelectorAll('.remove-user');
                trashButtons.forEach(button => button.classList.remove('hidden-button'));

                // Add event listener for removing currentUser only if cell is highlighted
                selectedCell.querySelectorAll('.remove-user').forEach(button => {
                    button.addEventListener('click', () => {
                        const userDiv = button.parentElement;
                        userDiv.remove();

                        // Add "Add user1" button if it doesn't exist
                        if (!selectedCell.querySelector('.add-user')) {
                            const addUserBtn = document.createElement('button');
                            addUserBtn.className = 'btn btn-sm btn-success add-user';
                            addUserBtn.textContent = 'Add user1';
                            addUserBtn.addEventListener('click', () => {
                                selectedCell.innerHTML += `<div class="current-user">${currentUser} <button class='btn btn-sm btn-danger remove-user hidden-button'><i class='bi bi-trash'></i></button></div>`;
                                addUserBtn.remove();
                            });
                            selectedCell.appendChild(addUserBtn);
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>



