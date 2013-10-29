function C=CalcEntr(Y);
% функция для запуска энтропий с тулбокса trius_entropy, myentropy. 
% В ней можно вручную изменять параметры запуска (для каждого метода свои:
% states - кол-во состояний (интервалов прибыльности для вероятностей)
% b - интервал для определения состояний (-b, b). Значение b - в единицах
% стандартного отклонения.
%states=3;
%b=1;
%figure;
%plot(Y);
%C=LZMeasureComplexity(Y,states,b);

%Перемикач виду ентропії. Закоментувати зайві і розкомментувати потрібний
%рядок

%typeentr=1; % Approximate Entropy: функція MyApen_new
typeentr=2; % Permutation Entropy: функція permen
%typeentr=3; % Sample Entropy: функція sampen 
%typeentr=4; % Shannon and Tsallis Entropy: функція TSentropy 
%typeentr=5; %Renyi entropy: функція renyi

% Функції викликаються з папки Trius_entropy, в даному тулбоксі вони не
% дублюються. Папка Trius_entropy має бути додана до шляхів matlab

% список энтропий для внедрения:
% cross_sampen.m
% Myapen_new.m 343:e=MyApen_new(ryad1,m,r); 

global stDev;


if (typeentr==1)
    m=2; %Довжина підряду. Була порожньою...
    r=0.2*stDev; %std(Y); % коефіцієнт подібності. Обчислюється...
    C=MyApen_new(Y,m,r);
    C=C(m);
end

% permen.m %502:[e]=permen(ryad1,m,r);
if (typeentr==2)
    m=3; %Довжина підряду. (довжина памяті, розмірність лагового вектору)
    L=1; % L - лаг 
    C=permen(Y,m,L);
end

% sampen.m %392: [e,se,A,B]=sampen(ryad1,m,r); entropymas(p)=e(m);
if (typeentr==3)
    m=2; %Довжина підряду. Була порожньою...
    r=0.2*stDev; %std(Y); % коефіцієнт подібності. Обчислюється...% Myapen_new.m 343:e=MyApen_new(ryad1,m,r); 
    C=sampen(Y,m,r);
    C=C(m);
end

% TSentropy.m % 687:e=TSentropy(ryad1,qparam);
if (typeentr==4)
    qparam=1;
    C=TSentropy(Y,qparam);
end

%
if (typeentr==5)
    a=0.5;
    C=renyi(Y,a);
end

% sampenc.m
% sampense.m


