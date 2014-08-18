{% extends "/layout/base.php" %}

{% block content %}
	<div class="container" style="text-align: center;">
		<h4>{{ test_variable }}</h4>
		<p><img src="{{ static_url }}img/it-works.png" width="221" height="86" /></p>
	</div>
{% endblock %}
