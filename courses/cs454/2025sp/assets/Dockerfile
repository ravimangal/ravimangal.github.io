# Use Ubuntu Jammy as base image
FROM ubuntu:jammy

# Install necessary packages (including Java Development Kit), including recommended ones, and handle missing package sources
RUN apt-get update && apt-get upgrade -y

# RUN    apt-get install -y --fix-missing openjdk-11-jdk

RUN if apt-get install -y --fix-missing openjdk-11-jdk; then \
      echo "Command succeeded"; \
    else \
      echo "Command failed, but continuing"; \
    fi

RUN apt --fix-broken -y install

# Set JAVA_HOME dynamically and update PATH
RUN JAVA_HOME=$(dirname $(dirname $(readlink -f $(which java)))) && \
    echo "export JAVA_HOME=$JAVA_HOME" >> ~/.bashrc && \
    echo "export PATH=$PATH:$JAVA_HOME/bin" >> ~/.bashrc

# Install Scala depending on the architecture
RUN    apt-get install -y --fix-missing curl
RUN if [ "$(uname -m)" = "aarch64" ]; then \
        curl -fL https://github.com/VirtusLab/coursier-m1/releases/latest/download/cs-aarch64-pc-linux.gz | gzip -d > cs; \
    else \
        curl -fL https://github.com/coursier/coursier/releases/latest/download/cs-x86_64-pc-linux.gz | gzip -d > cs; \
    fi && \
    chmod +x cs && ./cs setup -y && ./cs install scala:2.13.15 scalac:2.13.15
RUN echo "export PATH=$PATH:/root/.local/share/coursier/bin" >> ~/.bashrc

# Add PPA for newer Python versions and install Python
RUN if apt-get install -y --fix-missing software-properties-common; then \
      echo "Command succeeded"; \
    else \
      echo "Command failed, but continuing"; \
    fi

RUN apt --fix-broken -y install

RUN ln -snf /usr/share/zoneinfo/$CONTAINER_TIMEZONE /etc/localtime && echo $CONTAINER_TIMEZONE > /etc/timezone

RUN add-apt-repository ppa:deadsnakes/ppa && \
    apt-get update

RUN if apt-get install -y --fix-missing python3.12 python3-pip; then \
      echo "Command succeeded"; \
    else \
      echo "Command failed, but continuing"; \
    fi

RUN apt --fix-broken -y install

# Install Jupyter and set up Jupyter Scala kernel
RUN pip install notebook && \
    ./cs launch --use-bootstrap almond --scala 2.13 -- --install

RUN jupyter notebook --generate-config && \
    echo "c.ServerApp.token = ''" >> /root/.jupyter/jupyter_notebook_config.py && \
    echo "c.ServerApp.password = ''" >> /root/.jupyter/jupyter_notebook_config.py

# Set default work directory
ENV PATH="/root/.local/share/coursier/bin:${PATH}"

WORKDIR /home/cs454/
CMD ["jupyter", "notebook", "--ip=0.0.0.0", "--allow-root", "--no-browser", "--port=8012"]
