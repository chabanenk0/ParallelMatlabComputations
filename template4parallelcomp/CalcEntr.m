function C=CalcEntr(Y);
% ������� ��� ������� �������� � �������� trius_entropy, myentropy. 
% � ��� ����� ������� �������� ��������� ������� (��� ������� ������ ����:
% states - ���-�� ��������� (���������� ������������ ��� ������������)
% b - �������� ��� ����������� ��������� (-b, b). �������� b - � ��������
% ������������ ����������.
%states=3;
%b=1;
%figure;
%plot(Y);
%C=LZMeasureComplexity(Y,states,b);

%��������� ���� �����ﳿ. ������������� ���� � ��������������� ��������
%�����

%typeentr=1; % Approximate Entropy: ������� MyApen_new
typeentr=2; % Permutation Entropy: ������� permen
%typeentr=3; % Sample Entropy: ������� sampen 
%typeentr=4; % Shannon and Tsallis Entropy: ������� TSentropy 
%typeentr=5; %Renyi entropy: ������� renyi

% ������� ������������ � ����� Trius_entropy, � ������ ������� ���� ��
% ����������. ����� Trius_entropy �� ���� ������ �� ������ matlab

% ������ �������� ��� ���������:
% cross_sampen.m
% Myapen_new.m 343:e=MyApen_new(ryad1,m,r); 

global stDev;


if (typeentr==1)
    m=2; %������� ������. ���� ���������...
    r=0.2*stDev; %std(Y); % ���������� ��������. ������������...
    C=MyApen_new(Y,m,r);
    C=C(m);
end

% permen.m %502:[e]=permen(ryad1,m,r);
if (typeentr==2)
    m=3; %������� ������. (������� �����, ��������� �������� �������)
    L=1; % L - ��� 
    C=permen(Y,m,L);
end

% sampen.m %392: [e,se,A,B]=sampen(ryad1,m,r); entropymas(p)=e(m);
if (typeentr==3)
    m=2; %������� ������. ���� ���������...
    r=0.2*stDev; %std(Y); % ���������� ��������. ������������...% Myapen_new.m 343:e=MyApen_new(ryad1,m,r); 
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


