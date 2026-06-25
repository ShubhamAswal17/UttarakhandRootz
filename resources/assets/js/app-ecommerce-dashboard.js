'use strict';

(function () {
  let cardColor, labelColor, headingColor, borderColor;

  if (isDarkStyle) {
    cardColor = config.colors_dark.cardColor;
    labelColor = config.colors_dark.textMuted;
    headingColor = config.colors_dark.headingColor;
    borderColor = config.colors_dark.borderColor;
  } else {
    cardColor = config.colors.cardColor;
    labelColor = config.colors.textMuted;
    headingColor = config.colors.headingColor;
    borderColor = config.colors.borderColor;
  }

  /* -----------------------------
   * DEBUG DATA (IMPORTANT)
   * ----------------------------- */
  console.log("Revenue RAW:", monthlyRevenue);
  console.log("Expense RAW:", monthlyExpense);

  // ✅ FORCE NUMBER CONVERSION (VERY IMPORTANT)
  const revenueData = (monthlyRevenue || []).map(Number);
  const expenseData = (monthlyExpense || []).map(Number);

  console.log("Revenue FIXED:", revenueData);
  console.log("Expense FIXED:", expenseData);

  /* -----------------------------
   * TOTAL REVENUE CHART (FIXED)
   * ----------------------------- */
  const totalRevenueChartEl = document.querySelector('#totalRevenueChart');

  if (totalRevenueChartEl) {
    const totalRevenueChart = new ApexCharts(totalRevenueChartEl, {
      chart: {
        type: 'bar',
        stacked: true,
        height: 350
      },

      series: [
        {
          name: 'Revenue',
          data: revenueData
        },
        {
          name: 'Expense',
          data: expenseData
        }
      ],

      colors: [config.colors.primary, config.colors.warning],

      plotOptions: {
        bar: {
          columnWidth: '45%',
          borderRadius: 6
        }
      },

      dataLabels: {
        enabled: false
      },

      xaxis: {
        categories: [
          'Jan','Feb','Mar','Apr','May','Jun',
          'Jul','Aug','Sep','Oct','Nov','Dec'
        ]
      },

      /* ✅ FIXED Y-AXIS (THIS IS YOUR MAIN ISSUE) */
      yaxis: {
        min: 0,
        forceNiceScale: true,
        labels: {
          show: true,
          formatter: function (val) {
            return val.toLocaleString(); // 1200 → 1,200
          },
          style: {
            colors: labelColor
          }
        }
      },

      tooltip: {
        y: {
          formatter: function (val) {
            return "₹ " + val.toLocaleString();
          }
        }
      },

      legend: {
        position: 'top'
      }
    });

    totalRevenueChart.render();
  }

})(); 