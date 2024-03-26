// Line chart 1
 function calculateExpenseGraphData(){
  sendMinorData('calculate_expenses_data',function(data){

   data = JSON.parse(data)
   chartData = []
   for(var el in data){
   if(data[el]!= 0)
    data[el] = Math.random()*2000
    chartData.push(data[el])
   }
   console.log(chartData)
   return chartData
  })
  
  }


 var chartData=calculateExpenseGraphData()
setTimeout(function(){
let myChart = document.getElementById("lineChart").getContext("2d");
var gradient = myChart.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, 'rgba(207, 207, 207,1)');   
gradient.addColorStop(1, 'rgba(207, 207, 207,0)');
let chart = new Chart(myChart,{
    type : "line",
    data : {
        labels : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets : [{
            backgroundColor : gradient,
            label:'',
           // borderColor : '#7f7f81',
            strokeColor : "rgba(121, 121, 122,1)",
            pointRadius : 0,
            lineTension:0,
            data : chartData                //[60,75,59,65,73,80,59,55,62,93,45]
        }]
    },
    options : {
        scales: {
            
            yAxes: [{
                gridLines: {
                    color: "rgba(0, 0, 0, 0)",
                }   
            }]
        }
    }
})
},1400)

let myChart10 = document.getElementById("pPieChart2").getContext("2d");

let chart10 = new Chart(myChart10,{
    type : "polarArea",
    data : {
        labels : ["School fees", "Government", "Teachers", "Banks"],
        datasets : [{
                label : 'First Dataset',
                backgroundColor: [
                    "#6B5CAB",
                    "#059BFF",
                    "#7C667B",
                    "#39160D",
                    "#41A970"
                ], 
                data : [120, 50, 140, 180, 100]
            }
        ]
    }
})
// Line chart 2
let myChart1 = document.getElementById("lineChart1").getContext("2d");

let chart1 = new Chart(myChart1,{
    type : "line",
    data : {
        labels : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets : [{
                label : 'Sales',
                backgroundColor: "#28DEBB", 
                borderColor: "#28DEBB",
                pointHoverBackgroundColor: "#fff", 
                pointBorderWidth: 1,
                data : [31, 74, 6, 39, 20, 85, 7]
            },
            {
                label : 'Ravenue',
                backgroundColor: "#83ECCB",
                borderColor: "#83ECCB",
                pointHoverBackgroundColor: "#fff",  
                pointBorderWidth: 1,
                
                data : [82, 23, 66, 9, 99, 4, 2]
            }
        ]
    }
})

// Bar chart 1
let myChart2 = document.getElementById("barChart").getContext("2d");

let chart2 = new Chart(myChart2,{
    type : "bar",
    data : {
        labels : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets : [{
                label : '# of Votes',
                backgroundColor: "#6B5CAB", 
                data : [31, 74, 6, 39, 20, 85, 7]
            },
            {
                label : '# of Votes',
                backgroundColor: "#059BFF",
                data : [82, 23, 66, 9, 99, 4, 2]
            }
        ]
    },options: {
        scales: {
            yAxes:[ {
                    ticks: {
                        beginAtZero: !0
                    }
                }
            ]
        }
    }
})

// Bar chart 2
let myChart3 = document.getElementById("barChart1").getContext("2d");

let chart3 = new Chart(myChart3,{
    type : "bar",
    data : {
        labels : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets : [{
                label : '# of Votes',
                backgroundColor: [
                    "rgba(253,108,204,0.5)",
                    "rgba(115,218,141,0.5)",
                    "rgba(54,113,225,0.5)",
                    "rgba(240,5,114,0.5)",
                    "rgba(148,252,231,0.5)",
                    "rgba(131,185,253,0.5)",
                    "rgba(206,121,183,0.5)"
                ], 
                borderColor: [
                    "#FD6CCC",
                    "#73DA8D",
                    "#3671E1",
                    "#F00572",
                    "#94FCE7",
                    "#83B9FD",
                    "#CE79B7"
                ],
                borderWidth : 2,
                data : [31, 74, 6, 39, 20, 85, 7]
            }
        ]
    },
    options: {
        scales: {
            yAxes:[ {
                    ticks: {
                        beginAtZero: !0
                    }
                }
            ]
        }
    }
})

// Bar chart 3
let myChart4 = document.getElementById("barChart2").getContext("2d");

let chart4 = new Chart(myChart4,{
    type : "horizontalBar",
    data : {
        labels : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets : [{
                label : '# of Votes',
                backgroundColor: "#6B5CAB", 
                data : [31, 74, 6, 39, 20, 85, 7]
            },
            {
                label : '# of Votes',
                backgroundColor: "#059BFF",
                data : [82, 23, 66, 9, 99, 4, 2]
            }
        ]
    },
    options: {
        scales: {
            yAxes:[ {
                ticks: {
                        beginAtZero: !0
                    }
                }
            ]
        }
    }
})

// Mix Chart
let myChart5 = document.getElementById("mixChart").getContext("2d");

let chart5 = new Chart(myChart5,{
    type : "bar",
    data : {
        labels : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets : [{
                label : '# of Votes',
                backgroundColor: "#6B5CAB", 
                data : [31, 74, 6, 39, 20, 85, 7]
            },
            {
                label : '# of Votes',
                backgroundColor: "#059BFF",
                data : [82, 23, 66, 9, 99, 4, 2]
            },
            {
                type : "line",
                label : 'Ravenue',
                backgroundColor: "#83ECCB",
                borderColor: "#83ECCB",
                pointHoverBackgroundColor: "#fff",  
                pointBorderWidth: 1,
                data : [0, 50, 66, 45, 100, 15, 50]
            }
        ]
    }
})

// Radar Chart
let myChart6 = document.getElementById("radarChart").getContext("2d");

let chart6 = new Chart(myChart6,{
    type : "radar",
    data : {
        labels : ['Beginner', 'Ad-Hoc', 'String', 'Data Structures', 'Mathematics', 'Paradigms', 'Graph', 'Geometry', 'SQL'],
        datasets : [{
                label : 'First Dataset',
                backgroundColor: "rgba(107,92,171,0.5)", 
                data : [20, 30, 40, 45, 35, 20, 30, 40, 10]
            },
            {
                label : 'Second Dataset',
                backgroundColor: "rgba(107,92,171,0.5)",
                data : [50, 20, 30, 40, 30, 50, 20, 10, 10]
            }
        ]
    }
})

// Doughnut Chart
let myChart7 = document.getElementById("rdoughnutChart").getContext("2d");

let chart7 = new Chart(myChart7,{
    type : "doughnut",
    data : {
        labels : ["Mozila", "IE", "Google Chrome", " Edge", "Safari"],
        datasets : [{
                label : 'First Dataset',
                backgroundColor: [
                    "#6B5CAB",
                    "#059BFF",
                    "#7C667B",
                    "#39160D",
                    "#41A970"
                ], 
                data : [120, 50, 140, 180, 100]
            }
        ]
    }
})

// Pie Chart
let myChart8 = document.getElementById("pieChart").getContext("2d");

let chart8 = new Chart(myChart8,{
    type : "pie",
    data : {
        labels : ["Mozila", "IE", "Google Chrome", " Edge", "Safari"],
        datasets : [{
                label : 'First Dataset',
                backgroundColor: [
                    "#6B5CAB",
                    "#059BFF",
                    "#7C667B",
                    "#39160D",
                    "#41A970"
                ], 
                data : [120, 50, 140, 180, 100]
            }
        ]
    }
})

// chart.canvas.parentNode.style.height = '128px';
// chart.canvas.parentNode.style.width = '128px';
// Polar Pie Chart
let myChart9 = document.getElementById("pPieChart").getContext("2d");

let chart9 = new Chart(myChart9,{
    type : "polarArea",
    data : {
        labels : ["Mozila", "IE", "Google Chrome", " Edge", "Safari"],
        datasets : [{
                label : 'First Dataset',
                backgroundColor: [
                    "#6B5CAB",
                    "#059BFF",
                    "#7C667B",
                    "#39160D",
                    "#41A970"
                ], 
                data : [120, 50, 140, 180, 100]
            }
        ]
    }
})

