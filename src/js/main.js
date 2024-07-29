function showSuccessBar(msg) {
    var successBar = document.getElementById('successBar');
    
    // Remove the error class and add the success class
    successBar.classList.remove('bg-danger');
    successBar.classList.add('bg-success');
    
    // Make the success bar visible
    successBar.style.bottom = '0px';
    successBar.innerHTML = '<i class="fas fa-check-circle pr-1"></i>   ' + msg;
    
    setTimeout(function() {
        successBar.style.bottom = '-50px';
    }, 3000);
}

function showErrorBar(msg) {
    var successBar = document.getElementById('successBar');
    
    // Remove the success class and add the error class
    successBar.classList.remove('bg-success');
    successBar.classList.add('bg-danger');
    
    // Make the error bar visible
    successBar.style.bottom = '0px';
    successBar.innerHTML = '<i class="fa-solid fa-circle-xmark pr-1"></i>   ' + msg;
    
    setTimeout(function() {
        successBar.style.bottom = '-50px';
    }, 3000);
}