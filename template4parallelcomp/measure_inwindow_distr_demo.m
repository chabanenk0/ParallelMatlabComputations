infilename='dax_91.txt';
measurefunction='[c1,c2,c3]=shannon(y_fragment);';
wind=250;
methodid=12;% ����� ������, ������� �������� ���������� ����. ��������� ����� ���������� ���������� ������ �� �����.
generate_tasks(infilename,measurefunction,wind,methodid);
