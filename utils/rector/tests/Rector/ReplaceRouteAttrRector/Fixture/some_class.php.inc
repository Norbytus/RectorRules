<?php

namespace Utils\Rector\Tests\Rector\ReplaceRouteAttrRector\Fixture;

#[\Attribute]
class Test
{
}

?>
-----
<?php

namespace Utils\Rector\Tests\Rector\ReplaceRouteAttrRector\Fixture;

#[\SensitiveParameter]
class Test
{
}

?>
