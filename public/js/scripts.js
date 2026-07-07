const ctx = document.getElementById('ventasChart');
if(ctx){
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Ene','Feb','Mar','Abr'],
        datasets: [{ data: [10,20,30,40] }]
    }
});
}

const ctx2 = document.getElementById('stockChart');
if(ctx2){
new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: ['Stock','Bajo','Agotado'],
        datasets: [{ data: [80,15,5] }]
    }
});
}
