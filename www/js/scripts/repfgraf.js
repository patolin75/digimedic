window.Apex = {
    chart: {
      foreColor: '#fff',
      toolbar: {
        show: false
      },
    },
    stroke: {
      width: 3
    },
    dataLabels: {
      enabled: false
    },
    /*tooltip: {
      theme: 'dark'
    },*/
    grid: {
      borderColor: "#ABDCFF",
      xaxis: {
        lines: {
          show: true
        }
      }
    }
  };
  
  var spark1 = {
    chart: {
      id: 'spark1',
      group: 'sparks',
      type: 'line',
      height: 80,
      sparkline: {
        enabled: true
      },
      dropShadow: {
        enabled: true,
        top: 1,
        left: 1,
        blur: 2,
        opacity: 0.2,
      }
    },
    series: [{
        data: [25, 66, 41, 59, 25, 44, 12]
    }],
    stroke: {
      curve: 'smooth'
    },
    markers: {
      size: 0
    },
    grid: {
      padding: {
        top: 20,
        bottom: 10,
        left: 110
      }
    },
    colors: ['#fff'],
    tooltip: {
      x: {
        show: false
      },
      y: {
        title: {
          formatter: function formatter(val) {
            return '';
          }
        }
      }
    }
  }
  
  new ApexCharts(document.querySelector("#spark1"), spark1).render();
  
  var optionsLine = {
    chart: {
      height: 328,
      type: 'line',
      zoom: {
        enabled: false
      },
      dropShadow: {
        enabled: true,
        top: 3,
        left: 2,
        blur: 4,
        opacity: 1,
      }
    },
    stroke: {
      curve: 'smooth',
      width: 2
    },
    
    series: [{
        name: "Dr. Malito",
        data: [1, 15, 26, 20, 33, 27]
      },
      {
        name: "Dr. Elver",
        data: [3, 33, 21, 42, 19, 32]
      },
      {
        name: "Dr. Larri",
        data: [0, 39, 52, 11, 29, 43]
      }
    ],

    title: {
      text: 'Costos',
      align: 'left',
      offsetY: 25,
      offsetX: 20
    },
    subtitle: {
      text: 'mes',
      offsetY: 55,
      offsetX: 20
    },
    markers: {
      size: 6,
      strokeWidth: 0,
      hover: {
        size: 9
      }
    },
    grid: {
      show: true,
      padding: {
        bottom: 0
      }
    },
    labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre','Noviembre','Diciembre'],
    xaxis: {
      tooltip: {
        enabled: false
      }
    },
    legend: {
      position: 'top',
      horizontalAlign: 'right',
      offsetY: -20
    }
  }
  
  var chartLine = new ApexCharts(document.querySelector('#line-adwords'), optionsLine);
  chartLine.render();
  
  