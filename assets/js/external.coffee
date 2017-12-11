(($) ->

    urlTest = /^(?:http|https|ftp):\/\/([^\/]+)/

    recentUrls = {};

    whitelist = [
        /^si\.edu$/
        /^.*\.si\.edu$/
        /^smithsonianeducation\.org$/
        /^.*\.smithsonianeducation\.org$/
    ]

    whitelisted = (hostname) ->
        return _.any(whitelist, (matcher) ->
            return matcher.test(hostname)
        )

    $(document).on('click.external_links', 'a[href]', (e) ->

        $this = $(this)
        href = $this.attr('href')
        target = $this.attr('target')

        matches = href.match(urlTest)

        checkMatches = matches && matches[1] != window.location.host && matches[1] != window.location.hostname
        checkOverride = $this.hasClass('-external-no-check') || recentUrls[href] || whitelisted(this.hostname)

        if(checkMatches && !checkOverride)
            e.preventDefault()
            recentUrls[href] = true

            CoreApp.Dialogs.Controller.openDialog('confirm', {
                header: 'You are about to leave Smithsonian Learning Lab.'
                content: 'The link you clicked is NOT part of Smithsonian Learning Lab, but we hope you come back soon.<br><br>
                <a href="'+href+'" target="'+target+'" class="-external-no-check dialog-close">'+$.wbr(href)+'</a>

                <br>
                <br>
                Would you like to proceed?
                '
                buttons: [{
                    text: 'No'
                    'class': ''
                }, {
                    text: 'Proceed'
                    'class': 'btn-primary'
                    callback: (e, dialog) =>
                        if(target)
                            window.open(href, target)
                        else
                            window.location = href
                }]
            })

            return false


        return true

    )
) jQuery