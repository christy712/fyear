<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Year Holidays</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Financial Year Holidays</h1>
        <form method="GET" action="{{ route('holidays') }}" id="holidayForm">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="country" class="form-label">Country</label>
                    <select name="country" id="country" class="form-select" onchange="updateYears()">
                        <option value="Ireland" {{ $country === 'Ireland' ? 'selected' : '' }}>Ireland</option>
                        <option value="UK" {{ $country === 'UK' ? 'selected' : '' }}>UK</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="year" class="form-label">Year</label>
                    <select name="year" id="year" class="form-select" onchange="document.getElementById('holidayForm').submit()">
                        @foreach ($years as $y)
                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                {{ $country === 'UK' ? "$y-" . ($y + 1) : $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <h3>Financial Year: {{ $yearDisplay }}</h3>
        <p><strong>Start Date:</strong> {{ $startDate->format('jS F Y') }}</p>
        <p><strong>End Date:</strong> {{ $endDate->format('jS F Y') }}</p>

        <h4>Public Holidays (Excluding Weekends)</h4>
        @if (count($holidays) > 0)
            <ul class="list-group">
                @foreach ($holidays as $holiday)
                    <li class="list-group-item">{{ $holiday['name'] }} - {{ $holiday['date'] }}</li>
                @endforeach
            </ul>
        @else
            <p>No public holidays found for this period (excluding weekends).</p>
        @endif
    </div>

    <script>
        function updateYears() {
            const country = document.getElementById('country').value;
            const yearSelect = document.getElementById('year');
            const years = @json($years);
            yearSelect.innerHTML = '';

            years.forEach(year => {
                const option = document.createElement('option');
                option.value = year;
                option.text = country === 'UK' ? `${year}-${year + 1}` : year;
                if (year == {{ $selectedYear }}) option.selected = true;
                yearSelect.appendChild(option);
            });

            document.getElementById('holidayForm').submit();
        }
    </script>
</body>
</html>