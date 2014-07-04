<!DOCTYPE html>
<html class="{% block html_class %}{{html_class}}{% endblock %}" lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>
        {% block title %}{{title}}{% endblock %}
    </title>
    {% include 'bit/styles.html' %}      
    {% block head %}
    {% endblock %}
    {% include 'bit/analytics.html' %}
  </head>

  <body>
    {% include 'bit/header.html' %}
    {% include 'bit/announcements.html' %}  
    {% block header %}
    {% endblock %}     
    {% block content %}
    {% endblock %}
    {% include 'bit/footer.html' %}
    {% include 'bit/scripts.html' %}
    {% block scripts %}
    {% endblock %}    
    <div id="overcast">
        <div class='wrapper'>
            {% block overcast %}
            {% endblock %}
        </div>  
    </div>
  </body>
</html>