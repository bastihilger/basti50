import './bootstrap'

window.addEventListener('load', function () {
    window.Echo.channel('party')
    .listenToAll((event, data) => {
        // do what you need to do based on the event name and data
        console.log(event, data)
    })
})
