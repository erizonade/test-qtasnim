const responSwalAlert = (position, icon, message) => {
    Swal.fire({
        position: `top-${position}`,
        icon: icon,
        title: message,
        showConfirmButton: false,
        timer: 1500
    })
}

const swalConfirmasion = (text, callback) => {
    Swal.fire({
        title: 'Apakah Benar?',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            callback()
        }
    })
}

const removeXhr = () => {
    $(".invalid-feedback").remove()
    $(".text-danger").remove()
    $(".was-validated").removeClass()
}

const removeForm = () => {
    $("input, select, textarea").val("")
}

const handleErrorXhr = (xhr) => {
    if (xhr.status == 422) {
        let errorLoop = Object.entries(xhr.responseJSON.errors)
        // console.info(xhr.responseJSON.errors)
        errorLoop.forEach((val, key) => {
            let resRequired = val[1].find((message) => {
                return message.includes('required')
            })
            console.info(val[0])
            $(`#${val[0]}`).closest('div').append(`<div class="${'text text-danger'}">${val[1]}</div>`)
        });
    } else {
        responSwalAlert('end', 'error', xhr.responseJSON.message)
    }
}

const validateInput = (input) => {
    const regex = /^[0-9]*$/;
    if (!regex.test(input.value)) {
        input.value = input.value.replace(/[^0-9]/g, '');
    }
}

const checkValue = (dataValue) => {
    let check = (dataValue == undefined || dataValue == 0 || dataValue == 'null' || dataValue == null ||
        dataValue == '')
    return check
}
