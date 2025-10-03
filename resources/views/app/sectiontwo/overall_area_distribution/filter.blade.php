<div class="card" style="margin-bottom: 10px;" id="filter-card">
    <!-- <div class="card-header">
        <h5 class="card-title">Filter</h5>
    </div> -->
    <!-- <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <label for="donors1" class="form-label" style="font-size: small;"><strong>Funding Source:</strong></label>
                <select class="form-select border" id="donors1" style="font-size: small;">
                    <option value="All">All</option>
                    @foreach (DB::table('ref_funds')->select('funds_code')->get() as $donor)
                    <option value="{{ $donor->funds_code }}">{{ $donor->funds_code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12">
                <label for="management1" class="form-label" style="font-size: small;"><strong>Type of Management:</strong></label>
                <select class="form-select border" id="management1" style="font-size: small;">
                    <option value="All">All</option>
                    @foreach (DB::table('ref_management')->select('management_desc')->get() as $management)
                    <option value="{{ $management->management_desc }}">{{ $management->management_desc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12">
                <label for="depdev1" class="form-label" style="font-size: small;"><strong>DEPDev:</strong></label>
                <select class="form-select border" id="depdev1" style="font-size: small;">
                    <option value="All">All</option>
                    @foreach (DB::table('ref_depdev')->select('depdev_desc')->get() as $depdev)
                    <option value="{{ $depdev->depdev_desc }}">{{ $depdev->depdev_desc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12">
                <label for="level1_1" class="form-label" style="font-size: small;"><strong>Health Area:</strong></label>
                <select class="form-select border" id="level1_1" style="font-size: small;">
                    <option value="All">All</option>
                    @foreach (DB::table('ref_level1')->select('level1_desc')->get() as $level1)
                    <option value="{{ $level1->level1_desc }}">{{ $level1->level1_desc }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div> -->

    <div class="card-header" style="background-color: #f0f0f0;">
        <h5 class="card-title">Select Chart Type</h5>
    </div>
    <div class="card-body">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="chart_type" id="bar_chart" value="bar" checked onclick="toggleChartType()">
            <label class="form-check-label" for="bar_chart">
                Bar
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="chart_type" id="pie_chart" value="pie" onclick="toggleChartType()">
            <label class="form-check-label" for="pie_chart">
                Pie
            </label>
        </div>
    </div>
</div>

<script>
function toggleChartType() {
    const selectedType = document.querySelector('input[name="chart_type"]:checked').value;

    // Get all chart containers
    const barContainers = document.querySelectorAll('.bar-chart-container');
    const doughnutContainers = document.querySelectorAll('.doughnut-chart-container');

    if (selectedType === 'bar') {
        // Show bar charts
        barContainers.forEach(container => {
            container.style.display = 'block';
        });
        // Hide doughnut charts
        doughnutContainers.forEach(container => {
            container.style.display = 'none';
        });
    } else {
        // Hide bar charts
        barContainers.forEach(container => {
            container.style.display = 'none';
        });
        // Show doughnut charts
        doughnutContainers.forEach(container => {
            container.style.display = 'block';
        });
    }
}

// Initialize charts on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set bar chart as checked by default
    document.getElementById('bar_chart').checked = true;

    // Hide all doughnut charts initially
    const doughnutContainers = document.querySelectorAll('.doughnut-chart-container');
    doughnutContainers.forEach(container => {
        container.style.display = 'none';
    });

    // Show all bar charts initially
    const barContainers = document.querySelectorAll('.bar-chart-container');
    barContainers.forEach(container => {
        container.style.display = 'block';
    });

    // Add event listeners to radio buttons
    document.querySelectorAll('input[name="chart_type"]').forEach(radio => {
        radio.addEventListener('change', toggleChartType);
    });
});
</script>



