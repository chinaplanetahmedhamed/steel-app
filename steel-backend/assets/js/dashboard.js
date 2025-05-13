document.addEventListener("DOMContentLoaded", function () {
  // PIE CHART
  var ctxPie = document.getElementById('customerPie');
  if (ctxPie) {
    new Chart(ctxPie.getContext('2d'), {
      type: 'pie',
      data: {
        labels: ['China', 'Egypt', 'India', 'UAE', 'USA'],
        datasets: [{
          data: [40, 20, 15, 15, 10],
          backgroundColor: ['#007bff', '#dc3545', '#ffc107', '#28a745', '#17a2b8']
        }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });
    }
  });

  // BAR CHART
  var ctxOrders = document.getElementById('customerOrders');
  if (ctxOrders) {
    new Chart(ctxOrders.getContext('2d'), {
      type: 'bar',
      data: {
        labels: ['Ahmed', 'Mohamed', 'Sara', 'Ali', 'John', 'Fatima', 'Mike', 'Zhang', 'Omar', 'Linda'],
        datasets: [{
          label: 'Orders',
          data: [12, 10, 9, 8, 7, 6, 5, 4, 3, 2],
          backgroundColor: '#007bff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { yAxes: [{ ticks: { beginAtZero: true } }] }
      }
    });
  }

  // WORLD MAP
  if (document.getElementById('world-map')) {
    $('#world-map').vectorMap({
      map: 'world_mill',
      zoomOnScroll: false,
      panOnDrag: false,
      backgroundColor: 'transparent',
      regionStyle: {
        initial: {
          fill: '#e4e4e4',
          'fill-opacity': 0.9,
          stroke: 'none'
        }
      },
      series: {
        regions: [{
          values: { EG: 300, CN: 500, IN: 400, US: 250, AE: 350 },
          scale: ['#C8EEFF', '#0071A4'],
          normalizeFunction: 'polynomial'
        }]
      },
      onRegionTipShow: function(e, el, code){
        el.html(el.html() + ' (Customers: ' + (this.series.regions[0].values[code] || 0) + ')');
      }
    });
  }


// SORTABLEJS for my-table
const sortableElement = document.getElementById('my-table');
if (sortableElement) {
  new Sortable(sortableElement, {
    animation: 150,
    handle: '.handle',
  });
}

// SORTABLEJS for dashboard widgets
const widgetContainer = document.getElementById('dashboard-widgets');
if (widgetContainer) {
  new Sortable(widgetContainer, {
    animation: 150,
    handle: '.card-header, .small-box',
    ghostClass: 'sortable-ghost',
    onEnd: () => {
      const order = Array.from(widgetContainer.children).map(el => el.id);
      fetch('/api/save-widget-order', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(order)
      });
    }
  });
}