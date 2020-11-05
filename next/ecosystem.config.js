module.exports = {
    apps : [{
        name        : "network",
        script      : "./server.js",
        watch       : true,
        env: {
            "NODE_ENV": "production",
        }
    }]
}



