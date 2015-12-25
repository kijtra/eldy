# Eldy
The [JSON-LD](http://json-ld.org/) ( [schema.org](http://schema.org/) ) helper.

## Example
[Event](http://schema.org/Event) type example

```php
include('/path/to/Eldy/autoload.php');

$schema = new Eldy\Schema(array(// data setting if you need
    'date' => '2013-09-14T21:30',
    'postal' => '80209',
));

$schema->Event(function() {
    $this->name = 'Typhoon with Radiation City';
    $this->startDate = $this->data('date');// use data

    $this->location = $this->Place(function() {
        $this->sameAs = 'http://www.hi-dive.com';
        $this->name = 'The Hi-Dive';
        $this->address = $this->PostalAddress(function() {
            $this->streetAddress = '7 S. Broadway';
            $this->addressLocality = 'Denver';
            $this->addressRegion = 'CO';
            $this->postalCode = $this->data('postal');// use data
        });

        $this->url = 'wells-fargo-center.html';
    });

    $this->performer = $this->MusicGroup(function() {
        $this->name = 'Typhoon';
        $this->sameAs = 'http://en.wikipedia.org/wiki/Typhoon_(American_band)';
    });

    // you can use same property name
    $this->performer = $this->MusicGroup(function() {
        $this->name = 'RadiationCity';
        $this->sameAs = 'http://en.wikipedia.org/wiki/Radiation_City';
    });

    $this->offers = $this->Offer(function() {
        $this->availability = 'http://schema.org/LimitedAvailability';
        $this->price = '$13.00';
        $this->url = 'http://www.ticketfly.com/purchase/309433';
    });

    $this->typicalAgeRange = '18+';
});

echo $schema->pretty();
```

Output HTML

```html
<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "Event",
    "name": "Typhoon with Radiation City",
    "startDate": "2013-09-14T21:30",
    "location": {
        "@type": "Place",
        "sameAs": "http://www.hi-dive.com",
        "name": "The Hi-Dive",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "7 S. Broadway",
            "addressLocality": "Denver",
            "addressRegion": "CO",
            "postalCode": "80209"
        },
        "url": "wells-fargo-center.html"
    },
    "performer": [
        {
            "@type": "MusicGroup",
            "name": "Typhoon",
            "sameAs": "http://en.wikipedia.org/wiki/Typhoon_(American_band)"
        },
        {
            "@type": "MusicGroup",
            "name": "RadiationCity",
            "sameAs": "http://en.wikipedia.org/wiki/Radiation_City"
        }
    ],
    "offers": {
        "@type": "Offer",
        "availability": "http://schema.org/LimitedAvailability",
        "price": "$13.00",
        "url": "http://www.ticketfly.com/purchase/309433"
    },
    "typicalAgeRange": "18+"
}
</script>
```
