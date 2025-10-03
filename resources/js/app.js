import './bootstrap';
import '../css/project-effects.css';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

// for js or side navbar
$('.nav-item a').click(function () {
    $(this).parent().find('ul').toggleClass('d-none');
});

// for index page (projects)
document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggle-inputs');
    const inputFields = document.getElementById('input-fields');

    // Initially hide the input fields and implementation rows
    inputFields.style.display = 'none';
    const implementationRows = document.querySelectorAll('.implementation-row');
    const levelRows = document.querySelectorAll('.level-row');
    const financialRows = document.querySelectorAll('.financial-row');
    const physicalRows = document.querySelectorAll('.physical-row');

    // Hide all rows by default
    [implementationRows, levelRows, financialRows, physicalRows].forEach(rows => {
        rows.forEach(row => {
            row.style.display = 'none';
        });
    });

    toggleButton.addEventListener('click', function () {
        if (inputFields.style.display === 'none') {
            inputFields.style.display = 'block';
            toggleButton.innerHTML = '<i class="fa fa-eye-slash" aria-hidden="true"></i> Hide Input Fields';
        } else {
            inputFields.style.display = 'none';
            toggleButton.innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i> Show Input Fields';
        }
    });

    const projectRows = document.querySelectorAll('.project-row');

    // Add cursor pointer style to project rows
    projectRows.forEach(row => {
        row.style.cursor = 'pointer';
        // Add hover effect class
        row.classList.add('project-row-hover');
    });

    projectRows.forEach(row => {
        row.addEventListener('click', function (event) {
            // Add effect to the clicked row with transition
            projectRows.forEach(r => {
                r.classList.remove('active-row');
                r.style.transition = 'all 0.3s ease';
            });
            this.classList.add('active-row');

            // Add a ripple effect on click
            const ripple = document.createElement('div');
            ripple.classList.add('ripple');

            // Get click position relative to the row
            const rect = this.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;

            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';

            this.appendChild(ripple);

            // Remove ripple after animation
            setTimeout(() => {
                ripple.remove();
            }, 1000);

            // Add click animation
            this.classList.add('row-clicked');
            setTimeout(() => {
                this.classList.remove('row-clicked');
            }, 300);

            // Get all data attributes correctly
            const projectId = this.getAttribute('data-project-id');
            const projectName = this.getAttribute('data-project-name');
            const shortTitle = this.getAttribute('data-short-title');
            const fundingSource = this.getAttribute('data-funding-source');
            const depdev = this.getAttribute('data-depdev');
            const management = this.getAttribute('data-management');
            const gph = this.getAttribute('data-gph');
            const fundType = this.getAttribute('data-fund-type');
            const deskOfficer = this.getAttribute('data-desk-officer');
            const alignment = this.getAttribute('data-alignment');
            const environmental = this.getAttribute('data-environmental');
            const healthFacility = this.getAttribute('data-health-facility');
            const developmentObjectives = this.getAttribute('data-development-objectives');
            const sector = this.getAttribute('data-sector');
            const status = this.getAttribute('data-status');
            const sites = this.getAttribute('data-sites');
            const outcome = this.getAttribute('data-outcome');

            // Populate basic fields
            document.getElementById('project_id').value = projectId;
            document.getElementById('project_name').value = projectName;
            document.getElementById('short_title').value = shortTitle;

            // Populate funding source (both hidden and visible inputs)
            const fundingSourceInput = document.getElementById('funding_source_input');
            const fundingSourceHidden = document.getElementById('funding_source');
            if (fundingSourceInput && fundingSourceHidden) {
                fundingSourceInput.value = fundingSource;
                fundingSourceHidden.value = fundingSource;
            }

            document.getElementById('depdev').value = depdev;
            document.getElementById('management').value = management;
            document.getElementById('gph').value = gph;
            document.getElementById('fund_type').value = fundType;
            document.getElementById('desk_officer').value = deskOfficer;
            document.getElementById('environmental').value = environmental;
            document.getElementById('development_objectives').value = developmentObjectives;
            document.getElementById('status').value = status;
            document.getElementById('sites').value = sites;
            document.getElementById('outcome').value = outcome;

            // Populate the checkboxes for sector
            const sectorArray = sector ? sector.split(',').map(s => s.trim()) : [];
            const sectorCheckboxes = document.querySelectorAll('input[name="sector[]"]');
            sectorCheckboxes.forEach(checkbox => {
                checkbox.checked = sectorArray.includes(checkbox.value);
            });

            // Populate the checkboxes for alignment
            const alignmentArray = alignment ? alignment.split(',').map(a => a.trim()) : [];
            const alignmentCheckboxes = document.querySelectorAll('input[name="alignment[]"]');
            alignmentCheckboxes.forEach(checkbox => {
                checkbox.checked = alignmentArray.includes(checkbox.value);
            });

            // Populate the checkboxes for health facility
            const healthFacilityArray = healthFacility ? healthFacility.split(',').map(h => h.trim()) : [];
            const healthFacilityCheckboxes = document.querySelectorAll('input[name="health_facility[]"]');
            healthFacilityCheckboxes.forEach(checkbox => {
                checkbox.checked = healthFacilityArray.includes(checkbox.value);
            });

            // Populate the input fields in the other tabs
            const tabs = ['implementation', 'levels', 'financial', 'physical'];
            tabs.forEach(tab => {
                const tabElement = document.getElementById(tab);
                if (tabElement) {
                    tabElement.querySelector('#project_id').value = projectId;
                    tabElement.querySelector('#project_name').value = projectName;
                }
            });

            // Filter implementation schedule
            let hasData = false;

            [implementationRows, levelRows, financialRows, physicalRows].forEach(rows => {
                rows.forEach(row => {
                    if (row.getAttribute('data-project-id') === projectId) {
                        row.style.display = ''; // Show the row
                        hasData = true;
                    } else {
                        row.style.display = 'none'; // Hide the row
                    }
                });
            });

            // Show or hide the no data message
            const noDataRow = document.getElementById('no-data-row');
            noDataRow.style.display = hasData ? 'none' : '';

            // Set the hidden input for update
            document.getElementById('update_project_id').value = projectId;

            // Disable the Add button and enable Update button
            document.querySelector('button[name="add"]').disabled = true;
            document.querySelector('button[name="update"]').disabled = false;
        });
    });

    // Handle case when no project row is clicked
    const noDataRow = document.getElementById('no-data-row');
    noDataRow.style.display = ''; // Show no data message initially

    // Reset the form to allow adding a new project
    function resetForm() {
        document.getElementById('project_id').value = '';
        document.getElementById('project_name').value = '';
        // ... reset other fields
        document.querySelector('button[name="add"]').disabled = false;
        document.querySelector('button[name="update"]').disabled = true;

        // Show no data message when resetting the form
        noDataRow.style.display = ''; // Show no data message
    }
});
