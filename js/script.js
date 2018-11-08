// Variable
fetch('index.php',  { method : 'POST', body: data } )
    .then ( (result)=> result.text() ) 
    .then ( (result) => {
        console.log(result);
    });
